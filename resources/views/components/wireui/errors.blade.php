@if ($hasErrors($errors))
    <div {{ $attributes->merge(['class' => 'rounded-lg bg-red-50 p-4 mt-2']) }}>
        <div class="flex items-center pb-3 border-b-2 border-red-200 ">

            <i class="ico icon-error mr-3 text-red-500 text-lg"></i>

            <span class="text-sm font-semibold text-red-800">
                {{ str_replace('{errors}', $count($errors), $title($errors)) }}
            </span>
        </div>

        <div class="ml-5 pl-1 mt-2">
            <ul class="list-disc space-y-1 text-sm text-red-700">
                @foreach ($getErrorMessages($errors) as $message)
                    <li>{{ head($message) }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@else
    <div class="hidden"></div>
@endif
