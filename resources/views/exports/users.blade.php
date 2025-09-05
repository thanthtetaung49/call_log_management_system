<table class="w-full border-collapse mt-5">
    <thead>
        <tr class="bg-black text-white">
            <th class="p-2 border border-gray-300 text-left">No.</th>
            <th class="p-2 border border-gray-300 text-left">Name</th>
            <th class="p-2 border border-gray-300 text-left">Email</th>
            <th class="p-2 border border-gray-300 text-left">Role</th>
            <th class="p-2 border border-gray-300 text-left">Created at</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($users as $user)
            <tr class="hover:bg-gray-100">
                <td class="p-2 border border-gray-300">{{ $loop->iteration }}</td>
                <td class="p-2 border border-gray-300">{{ $user->name }}</td>
                <td class="p-2 border border-gray-300">{{ $user->email }}</td>
                <td class="p-2 border border-gray-300">{{ $user->role }}</td>
                <td class="p-2 border border-gray-300">
                    {{ \Carbon\Carbon::parse($user->created_at)->format('M d, H:i A') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
