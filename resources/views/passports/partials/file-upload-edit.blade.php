<div x-data="{ 
    url: '{{ $value ?? '' }}', 
    uploading: false, 
    progress: 0, 
    error: null,
    uploadFile(event) {
        const file = event.target.files[0];
        if (!file) return;

        this.uploading = true;
        this.error = null;
        this.progress = 0;

        const formData = new FormData();
        formData.append('file', file);

        fetch('{{ route('upload') }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (!response.ok) throw new Error('Upload failed');
            return response.json();
        })
        .then(data => {
            this.url = data.url;
            this.uploading = false;
        })
        .catch(err => {
            this.error = err.message;
            this.uploading = false;
        });
    }
}" class="col-span-1">
    <label class="block text-sm font-medium leading-6 text-gray-900">{{ $label }}</label>
    <div class="mt-2 flex justify-center rounded-lg border border-dashed border-gray-900/25 px-6 py-10"
        :class="url ? 'border-blue-500 bg-blue-50' : ''">
        <template x-if="!url">
            <div class="text-center">
                <svg class="mx-auto h-12 w-12 text-gray-300" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd"
                        d="M1.5 6a2.25 2.25 0 012.25-2.25h16.5A2.25 2.25 0 0122.5 6v12a2.25 2.25 0 01-2.25 2.25H3.75A2.25 2.25 0 011.5 18V6zM3 16.06V18c0 .414.336.75.75.75h16.5A.75.75 0 0021 18v-1.94l-2.69-2.689a1.5 1.5 0 00-2.12 0l-.88.879.97.97a.75.75 0 11-1.06 1.06l-5.16-5.159a1.5 1.5 0 00-2.12 0L3 16.061zm10.125-7.81a1.125 1.125 0 112.25 0 1.125 1.125 0 01-2.25 0z"
                        clip-rule="evenodd" />
                </svg>
                <div class="mt-4 flex text-sm leading-6 text-gray-600 justify-center">
                    <label
                        class="relative cursor-pointer rounded-md bg-white font-semibold text-blue-600 focus-within:outline-none focus-within:ring-2 focus-within:ring-blue-600 focus-within:ring-offset-2 hover:text-blue-500">
                        <span>Upload a file</span>
                        <input type="file" class="sr-only" @change="uploadFile">
                    </label>
                </div>
                <p class="text-xs leading-5 text-gray-600">PNG, JPG, PDF up to 10MB</p>

                <div x-show="uploading" class="mt-2 text-sm text-blue-600">Uploading...</div>
                <div x-show="error" class="mt-2 text-sm text-red-600" x-text="error"></div>
            </div>
        </template>

        <template x-if="url">
            <div class="text-center w-full">
                <img :src="url" class="mx-auto h-32 object-contain mb-2 rounded-md" alt="Preview">
                <a :href="url" target="_blank" class="text-sm text-blue-600 hover:underline break-all" x-text="url"></a>
                <button type="button" @click="url = ''"
                    class="mt-2 block w-full text-xs text-red-500 hover:text-red-700">Remove & Upload New</button>
            </div>
        </template>

        <input type="hidden" name="{{ $name }}" :value="url">
    </div>
    @error($name)
        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>