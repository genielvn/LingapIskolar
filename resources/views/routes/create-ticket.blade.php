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

                <x-select-input
                    :id="'category'"
                    :label="'Category'"
                    :value="request('category')"
                >
                    <x-option-input
                        :options="['Scholarship Inquiry', 'Financial Assistance', 'Documents Submission', 'Application Status', 'Technical Support', 'General Inquiry']"
                        select-name="category"
                    />
                </x-select-input>

                <x-text-box-input
                    :label="'Detailed Description'"
                    :id="'description'"
                    placeholder="Please explain the steps to reproduce the issue..."
                />

                <x-upload-input :id="'upload'" />

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
