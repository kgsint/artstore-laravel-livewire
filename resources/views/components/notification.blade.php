<div
x-data="{ body: ''}"
x-show="body.length"
x-on:notification.window="body = $event.detail[0]?.body || $event.detail.body; setTimeout(() => body = '', $event.detail[0]?.timeout || 2000)"
x-cloak
class="fixed z-10 h-12 top-10 mx-auto inset-0 flex items-center
w-full max-w-sm p-4 rounded-lg shadow text-gray-400 bg-gray-800">
        <div class="ml-3 text-sm font-normal" x-text="body">
        </div>
                <button
                    @click=" body='' "
                    class="ml-auto -mx-1.5 -my-1.5 bg-white text-gray-400 hover:text-gray-900 rounded-lg
                    focus:ring-2 focus:ring-gray-300 p-1.5 hover:bg-gray-100 inline-flex items-center justify-center
                    h-8 w-8 dark:text-gray-500 dark:hover:text-white dark:bg-gray-800 dark:hover:bg-gray-700"
                >
                    <span class="sr-only">Close</span>
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                </button>
</div>
