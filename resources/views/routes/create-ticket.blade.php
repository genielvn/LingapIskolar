@extends("layouts.main")

@section("main")
    <div class="flex w-full flex-col items-center justify-center gap-4">
        <div
            class="flex w-3xl flex-col gap-4 rounded-xl border-2 border-black bg-white p-8"
        >
            <h1 class="text-4xl font-bold">Submit a Support Ticket</h1>
            <form
                method="POST"
                action="/ticket/create"
                class="flex w-full flex-col gap-4"
            >
                @csrf
                <x-text-input
                    :type="'text'"
                    :label="'Subject'"
                    :id="'subject'"
                ></x-text-input>
                <x-text-box-input
                    :label="'Description'"
                    :id="'description'"
                ></x-text-box-input>
                <x-select-input :id="'category'">
                    <option disabled selected value="initial">Category</option>
                    <option value="scholarship">Scholarship</option>
                    <option value="inquiry">Inquiry</option>
                </x-select-input>

                <x-button :type="'submit'">Submit</x-button>
            </form>
        </div>
    </div>
@endsection
