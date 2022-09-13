<x-layout>

    <body class="bg-gradient-primary">
        <div class="container">
            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class="card o-hidden border-0 shadow-lg my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row">
                                <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>

                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center">
                                            <h1 class="h4 text-gray-900 mb-4">Welcome Back!</h1>
                                        </div>

                                        <form class="user" action="/login" method="post">
                                            @csrf

                                            @foreach ($formFields as $formField)
                                                <x-auth.form :name="$formField['name']" :label="$formField['label']" :fieldType="$formField['fieldType']">
                                                    @error($formField['name'])
                                                        <p>{{ $message }}</p>
                                                    @enderror
                                                </x-auth.form>
                                            @endforeach

                                            <button type="submit" class="btn btn-primary btn-user btn-block">
                                                Login
                                            </button>
                                        </form>

                                        <hr>

                                        <div class="text-center">
                                            <a class="small" href="/register">Create an Account!</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <x-partials._script />
    </body>

</x-layout>
