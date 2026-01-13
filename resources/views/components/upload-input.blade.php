<div class="flex flex-col gap-2">
    <label
        class="ml-1 text-xs font-bold tracking-widest text-zinc-500 uppercase"
    >
        Attachments (Optional)
    </label>
    <div class="flex w-full items-center justify-center">
        <label
            for="upload"
            class="flex h-32 w-full cursor-pointer flex-col items-center justify-center rounded-2xl border-2 border-dashed border-zinc-300 bg-zinc-50 transition-colors hover:bg-zinc-100"
        >
            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                <i class="bi bi-cloud-arrow-up mb-2 text-3xl text-zinc-400"></i>
                <p class="text-sm font-medium text-zinc-500">Click to upload</p>
                <p class="text-xs text-zinc-400">PNG, JPG or PDF (MAX. 5MB)</p>
            </div>
            <input
                type="file"
                {{
                    $attributes->merge([
                        "id" => $id,
                        "name" => $id,
                    ])
                }}
                class="hidden"
            />
        </label>
    </div>
</div>
