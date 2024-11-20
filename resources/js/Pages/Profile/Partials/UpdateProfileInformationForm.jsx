import InputError from "@/Components/InputError";

import { Transition } from "@headlessui/react";
import { Link, useForm, usePage } from "@inertiajs/react";
import { Input, Button } from "@nextui-org/react";
import SystemAlert from "@/Components/SystemAlert.jsx";

export default function UpdateProfileInformation({
    mustVerifyEmail,
    status,
    className = "",
}) {
    const { auth } = usePage().props;

    const { data, setData, patch, errors, processing, recentlySuccessful } =
        useForm({
            name: auth.user.name,
            email: auth.user.email,
        });

    const submit = (e) => {
        e.preventDefault();

        patch(route("profile.update"));
    };

    return (
        <section className={className}>
            <header>
                <h2 className="text-lg font-medium text-gray-900">
                    Profile Information
                </h2>

                <p className="mt-1 text-sm text-gray-600">
                    Update your account's profile information and email address.
                </p>
            </header>

            <form onSubmit={submit} className="mt-6 space-y-6">
                <div>
                    <Input
                        key="inside"
                        type="name"
                        label="Name"
                        variant="bordered"
                        labelPlacement="inside"
                        value={data.name}
                        onChange={(e) => setData("name", e.target.value)}
                          
                    />
                    <InputError className="mt-2" message={errors.name} />
                </div>

                <div>
                    <Input
                        key="inside"
                        type="email"
                        label="Email"
                        variant="bordered"
                        labelPlacement="inside"
                        value={data.email}
                        onChange={(e) => setData("email", e.target.value)}
                    />
                    <InputError className="mt-2" message={errors.email} />
                </div>

                {mustVerifyEmail && auth.user.email_verified_at === null && (
                    <div>
                        <p className="mt-2 text-sm text-gray-800">
                            Your email address is unverified.
                            <Link
                                href={route("verification.send")}
                                method="post"
                                as="button"
                                className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                            >
                                Click here to re-send the verification email.
                            </Link>
                        </p>

                        <SystemAlert />
                    </div>
                )}

                <div className="flex items-center gap-4">
                    <Button type="submit" color="primary" variant="flat" isDisabled={processing}>
                        SAVE
                    </Button>
                    <Transition
                        show={recentlySuccessful}
                        enter="transition ease-in-out"
                        enterFrom="opacity-0"
                        leave="transition ease-in-out"
                        leaveTo="opacity-0"
                    >
                        <p className="text-sm text-gray-600">Saved.</p>
                    </Transition>
                </div>
            </form>
        </section>
    );
}
