<div
  x-data="varChips({
    targetId: '{{ $targetId }}',
    statePath: '{{ $statePath }}',
    vars: @js($vars),
    dummy: @js($dummy),
  })"
  x-init="init()"
  class="mt-2 space-y-3"
>
  {{-- Chips variabel (dark + light ready) --}}
  <div class="flex flex-wrap items-center gap-2">
    <template x-for="v in vars" :key="v">
      <button type="button"
        @click="insert(v)"
        class="px-2.5 py-1 text-xs rounded-md border bg-white text-gray-800 border-gray-300 hover:bg-gray-50
               dark:bg-gray-800 dark:text-gray-100 dark:border-gray-700 dark:hover:bg-gray-700">
        <span x-text="`{${v}}`"></span>
      </button>
    </template>
    <span class="text-xs text-gray-500 dark:text-gray-400">Klik token untuk menyisipkan variabel.</span>
  </div>

  {{-- Preview WhatsApp --}}
  <div class="rounded-xl border border-gray-200 bg-gray-50 p-3
              dark:border-gray-700 dark:bg-gray-900/40">
    <div class="text-xs mb-2 text-gray-600 dark:text-gray-300">Preview WhatsApp</div>
    <div class="rounded-lg px-3 py-2 text-sm leading-6 whitespace-pre-wrap font-[ui-monospace] border border-gray-200 bg-white
                dark:bg-gray-800 dark:border-gray-700"
         x-text="render()"></div>
  </div>
</div>
