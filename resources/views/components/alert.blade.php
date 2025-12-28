<div class="bottom-0 right-0 mb-6 mr-6 fixed z-50">
    @if (session('success'))
        <div class="p-4 bg-green-50 border-l-4 border-green-500 text-green-800 border rounded-md">
            {{ session('success') }}
            <div class="mt-2 h-1 bg-green-200 overflow-hidden rounded">
                <div id="progress" class="h-full bg-green-500" style="width:0%"></div>
            </div>
        </div>
        <script>
            const script = document.currentScript;
            const alertDiv = script.parentElement;
            const bar = alertDiv.querySelector('#progress');

            requestAnimationFrame(() => {
                bar.style.transition = 'width 8s linear';
                bar.style.width = '100%';
            });

            setTimeout(() => {
                alertDiv.remove();
            }, 8000);
        </script>
@endif

@if (session('error'))
    <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 border rounded-md z-50">
        {{ session('error') }}
        <div class="mt-2 h-1 bg-red-200 overflow-hidden rounded">
            <div id="progress" class="h-full bg-red-500" style="width:0%"></div>
        </div>
    </div>
    <script>
        const script = document.currentScript;
        const alertDiv = script.parentElement;
        const bar = alertDiv.querySelector('#progress');

        requestAnimationFrame(() => {
            bar.style.transition = 'width 8s linear';
            bar.style.width = '100%';
        });

        setTimeout(() => {
            alertDiv.remove();
        }, 8000);
    </script>
@endif

@if ($errors->has('error'))
    <div class="p-4 bg-red-50 border-l-4 border-red-500 text-red-800 border rounded-md z-50">
        {{ $errors->first('error') }}
        <div class="mt-2 h-1 bg-red-200 overflow-hidden rounded">
            <div id="progress" class="h-full bg-red-500" style="width:0%"></div>
        </div>
    </div>
    <script>
        const script = document.currentScript;
        const alertDiv = script.parentElement;
        const bar = alertDiv.querySelector('#progress');

        requestAnimationFrame(() => {
            bar.style.transition = 'width 8s linear';
            bar.style.width = '100%';
        });

        setTimeout(() => {
            alertDiv.remove();
        }, 8000);
    </script>
@endif

@if (session('info'))
    <div class="p-4 bg-blue-50 border-l-4 border-blue-500 text-blue-800 border rounded-md z-50">
        {{ session('info') }}
        <div class="mt-2 h-1 bg-blue-200 overflow-hidden rounded">
            <div id="progress" class="h-full bg-blue-500" style="width:0%"></div>
        </div>
    </div>
    <script>
        const script = document.currentScript;
        const alertDiv = script.parentElement;
        const bar = alertDiv.querySelector('#progress');

        requestAnimationFrame(() => {
            bar.style.transition = 'width 8s linear';
            bar.style.width = '100%';
        });

        setTimeout(() => {
            alertDiv.remove();
        }, 8000);
    </script>
@endif
</div>
