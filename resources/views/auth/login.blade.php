<!doctype html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sign in · {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-full items-center justify-center bg-[#131f34] px-4">
    <div class="w-full max-w-sm rounded-2xl bg-white p-8 shadow-xl">
        <h1 class="text-xl font-semibold text-[#131f34]">AURCO CRM</h1>
        <p class="mt-1 text-sm text-slate-500">Sign in to manage the website.</p>

        <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-4">
            @csrf
            <div class="space-y-1.5">
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required autofocus
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-[#c8161e] focus:outline-none focus:ring-1 focus:ring-[#c8161e]">
                @error('email')<p class="text-xs text-red-600">{{ $message }}</p>@enderror
            </div>
            <div class="space-y-1.5">
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input id="password" name="password" type="password" required
                       class="w-full rounded-lg border border-slate-300 px-3 py-2 focus:border-[#c8161e] focus:outline-none focus:ring-1 focus:ring-[#c8161e]">
            </div>
            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1" class="rounded border-slate-300"> Stay signed in
            </label>
            <button class="w-full rounded-lg bg-[#c8161e] px-4 py-2.5 font-semibold text-white hover:bg-[#a8131a]">Sign in</button>
        </form>
    </div>
</body>
</html>
