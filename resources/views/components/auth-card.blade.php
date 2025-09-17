<div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100 px-4">

    <div class="w-full sm:max-w-md mt-6 px-6 py-12 bg-white shadow-md overflow-hidden sm:rounded-lg border-l-4 border-cyan-400">

        <div class="flex justify-center mb-6">
            {{ $logo }}
        </div>

        {{ $slot }}
    </div>
</div>
