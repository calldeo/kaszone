<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        /* Custom styles */
        .input-error {
            border-color: #f87171; /* border-red-400 */
            background-color: #fee2e2; /* bg-red-100 */
        }
        .error-message {
            color: #b91c1c; /* text-red-600 */
            font-size: 0.875rem; /* text-sm */
        }
        .custom-border {
            border-width: 2px; /* Adjust border width */
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 bg-white shadow-md rounded-lg">
        <h2 class="text-2xl font-bold mb-6 text-center text-gray-900">Daftar</h2>

        <form id="registrationForm" action="{{ url('/postregister') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full border custom-border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3" required>
                <p id="nameError" class="error-message hidden">Nama harus diisi.</p>
            </div>
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="mt-1 block w-full border custom-border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3" required>
                <p id="emailError" class="error-message hidden">Email harus diisi.</p>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700">Kata Sandi</label>
                <input type="password" name="password" id="password" class="mt-1 block w-full border custom-border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3" required>
                <p id="passwordError" class="error-message hidden">Kata sandi harus diisi.</p>
            </div>
            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full border custom-border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3" required>
                <p id="passwordConfirmationError" class="error-message hidden">Konfirmasi kata sandi harus diisi dan harus sama dengan kata sandi.</p>
            </div>
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Alamat</label>
                <input type="text" name="alamat" id="alamat" class="mt-1 block w-full border custom-border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3" required>
                <p id="nameError" class="error-message hidden">Alamat harus diisi.</p>
            </div>
            <div class="mb-6">
                <label for="level" class="block text-sm font-medium text-gray-700">Jenis Kelamin</label>
                <select name="kelamin" id="kelamin" class="mt-1 block w-full border custom-border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3" required>
                    <option value="" disabled selected>Jenis Kelamin</option>
                    <option value="laki-laki">Laki-Laki</option>
                    <option value="perempuan">Perempuan</option>
                </select>
            </div>
            <div class="mb-6">
                <label for="level" class="block text-sm font-medium text-gray-700">Peran</label>
                <select name="level" id="level" class="mt-1 block w-full border custom-border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 sm:text-sm py-3" required>
                    <option value="" disabled selected>Pilih peran</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="w-full py-2 px-4 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">Daftar</button>
            <a href="{{ url('/login') }}" class="block mt-4 text-center text-indigo-600 hover:text-indigo-800">Cancel</a>
        </form>

        <script>
            document.getElementById('registrationForm').addEventListener('submit', function (event) {
                let valid = true;

                // Get form inputs
                const name = document.getElementById('name');
                const email = document.getElementById('email');
                const password = document.getElementById('password');
                const passwordConfirmation = document.getElementById('password_confirmation');

                // Check if fields are empty
                if (!name.value.trim()) {
                    document.getElementById('nameError').classList.remove('hidden');
                    name.classList.add('input-error');
                    valid = false;
                } else {
                    document.getElementById('nameError').classList.add('hidden');
                    name.classList.remove('input-error');
                }

                if (!email.value.trim()) {
                    document.getElementById('emailError').classList.remove('hidden');
                    email.classList.add('input-error');
                    valid = false;
                } else {
                    document.getElementById('emailError').classList.add('hidden');
                    email.classList.remove('input-error');
                }

                if (!password.value.trim()) {
                    document.getElementById('passwordError').classList.remove('hidden');
                    password.classList.add('input-error');
                    valid = false;
                } else {
                    document.getElementById('passwordError').classList.add('hidden');
                    password.classList.remove('input-error');
                }

                if (!passwordConfirmation.value.trim()) {
                    document.getElementById('passwordConfirmationError').classList.remove('hidden');
                    passwordConfirmation.classList.add('input-error');
                    valid = false;
                } else if (passwordConfirmation.value !== password.value) {
                    document.getElementById('passwordConfirmationError').textContent = 'Konfirmasi kata sandi harus sama dengan kata sandi.';
                    document.getElementById('passwordConfirmationError').classList.remove('hidden');
                    passwordConfirmation.classList.add('input-error');
                    valid = false;
                } else {
                    document.getElementById('passwordConfirmationError').classList.add('hidden');
                    passwordConfirmation.classList.remove('input-error');
                }

                if (!valid) {
                    event.preventDefault(); // Prevent form from submitting if there are errors
                }
            });
        </script>
    </div>

</body>
</html>