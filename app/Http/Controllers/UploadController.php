<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Aws\S3\Exception\S3Exception;

class UploadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:jpeg,png,jpg,pdf|max:10240', // 10MB
        ]);

        $file = $request->file('file');
        $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

        try {
            // Get disk config for debugging
            $diskConfig = config('filesystems.disks.do');
            Log::info('DO Disk config', [
                'key' => substr($diskConfig['key'] ?? 'null', 0, 8) . '...',
                'region' => $diskConfig['region'] ?? 'null',
                'bucket' => $diskConfig['bucket'] ?? 'null',
                'endpoint' => $diskConfig['endpoint'] ?? 'null',
            ]);

            // Try to put file with explicit options
            $disk = Storage::disk('do');
            $path = 'uploads/' . $filename;

            $result = $disk->put($path, file_get_contents($file->getRealPath()));

            if (!$result) {
                Log::error('Upload failed: put returned false', [
                    'path' => $path,
                    'file_size' => $file->getSize(),
                ]);
                return response()->json(['error' => 'Upload failed - storage returned false'], 500);
            }

            // Construct URL manually
            $endpoint = config('filesystems.disks.do.url');
            $url = rtrim($endpoint, '/') . '/' . $path;

            Log::info('Upload success', ['path' => $path, 'url' => $url]);

            $signedUrl = $url;
            try {
                $signedUrl = $disk->temporaryUrl($path, now()->addMinutes(60));
            } catch (\Exception $e) {
                // Keep original URL if signing fails
            }

            return response()->json([
                'url' => $url,
                'signed_url' => $signedUrl
            ]);
        } catch (S3Exception $e) {
            Log::error('S3 Exception: ' . $e->getAwsErrorMessage(), [
                'code' => $e->getAwsErrorCode(),
                'request_id' => $e->getAwsRequestId(),
            ]);
            return response()->json(['error' => 'S3 Error: ' . $e->getAwsErrorMessage()], 500);
        } catch (\Exception $e) {
            Log::error('Upload exception: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
