@extends("layouts.main")
@extends("layouts.user-sidebar")

@section("main")
    <div
        class="flex min-h-[calc(100vh-80px)] w-full items-center justify-center p-6"
    >
        <div
            class="w-full max-w-2xl rounded-2xl border border-zinc-200 bg-white p-10 shadow-xl shadow-zinc-200/50"
        >
            <div class="mb-8 border-b border-zinc-100 pb-6 text-center">
                <h1
                    class="text-3xl font-black tracking-tight text-zinc-900 uppercase"
                >
                    Submit a Support Ticket
                </h1>
                <p class="mt-2 text-zinc-500">
                    Provide as much detail as possible so our agents can assist
                    you faster.
                </p>
            </div>

            <form
                method="POST"
                action="/ticket/create"
                class="flex w-full flex-col gap-6"
                enctype="multipart/form-data"
            >
                @csrf

                <x-text-box-input
                    :type="'text'"
                    :label="'Subject'"
                    :id="'subject'"
                    placeholder="Briefly describe your issue"
                />

                {{-- FIXED: Use category_id and pass $categories from controller --}}
                <div>
                    <label for="category_id" class="block text-sm font-medium text-zinc-700 mb-2">
                        Category
                    </label>
                    <select 
                        name="category_id" 
                        id="category_id" 
                        required
                        class="w-full rounded-lg border border-zinc-300 px-4 py-3 focus:border-red-500 focus:outline-none focus:ring-2 focus:ring-red-200"
                    >
                        <option value="">Select a category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <x-text-box-input
                    :label="'Detailed Description'"
                    :id="'description'"
                    placeholder="Please explain the steps to reproduce the issue..."
                />
                
                {{--  <x-upload-input :id="'upload'" /> --}} 

                <div class="mt-4 flex flex-col gap-3">
                    <x-button :type="'submit'" class="w-full py-4 text-lg">
                        Submit Ticket
                    </x-button>
                    <a
                        href="{{ route("dashboard") }}"
                        class="text-center text-sm font-bold text-zinc-400 transition hover:text-red-800"
                    >
                        Cancel and return to dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection