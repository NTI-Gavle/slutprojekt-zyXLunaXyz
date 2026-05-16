<aside class="hidden xl:block fixed right-0 top-0 h-screen w-96 border-l border-neutral-800 bg-black px-6 py-5 overflow-y-auto">
    <form action="explore.php" method="get" class="mb-5">
        <input
            type="text"
            name="q"
            placeholder="Search"
            class="w-full rounded-full bg-black border border-neutral-800 px-5 py-3 outline-none focus:border-sky-500"
        >
    </form>

    <section class="border border-neutral-800 rounded-2xl p-4 mb-4">
        <h2 class="font-bold text-lg mb-3">Today’s Posts</h2>

        <div class="space-y-4 text-sm text-neutral-300">
            <div>
                <p class="font-semibold text-white">Welcome to Z</p>
                <p class="text-neutral-500">Start posting and follow people.</p>
            </div>

            <div>
                <p class="font-semibold text-white">random nonsense</p>
                <p class="text-neutral-500">random nonsense</p>
            </div>

            <div>
                <p class="font-semibold text-white">random nonsense</p>
                <p class="text-neutral-500">random nonsense</p>
            </div>
        </div>
    </section>

    <section class="border border-neutral-800 rounded-2xl p-4">
        <h2 class="font-bold text-lg mb-3">Who to follow</h2>

        <div class="space-y-4">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-bold">Test</p>
                    <p class="text-sm text-neutral-500">@testuser</p>
                </div>
                <button class="rounded-full bg-white text-black text-sm font-bold px-4 py-1.5">
                    Follow
                </button>
            </div>

            <div class="flex items-center justify-between gap-3">
                <div>
                    <p class="font-bold">Test</p>
                    <p class="text-sm text-neutral-500">@testuser</p>
                </div>
                <button class="rounded-full bg-white text-black text-sm font-bold px-4 py-1.5">
                    Follow
                </button>
            </div>
        </div>
    </section>
        <canvas
            id="zClockCanvas"
            width="260"
            height="260"
            class="z-clock-canvas mb-4"
        >
        </canvas>
    </section>
</aside>