import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import {Button, Input, Textarea} from "@nextui-org/react";
import {useForm} from "@inertiajs/react";
import PasswordInput from "@/Components/Form/PasswordInput.jsx";
import InputError from "@/Components/InputError.jsx";
import {useEncrypted} from "@/Components/Encryption/WorkWithToken.jsx";

export default function TestEncryptionDataCreating() {
    const { encryptedData } = useEncrypted();

    const { post, reset, data, setData, errors, processing, isDirty } = useForm({
        name: '',
        username: '',
        password: '',
        url: '',
        note: ''
    })

    const saveLogin = (e) => {
        e.preventDefault();

        encryptedData((options) => {
            post(route('sensitive-data.store', {type: 'login'}), {
                preserveState: true,
                preserveScroll: true,
                onSuccess: () => reset(),
                ...options
            })
        });
    };

    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Test saving encrypted data
                </h2>
            }
        >

            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
                        <section className="space-y-6">
                            <form onSubmit={saveLogin} className="mt-6 space-y-6">
                                <div className="w-full flex flex-col gap-4">
                                    <Input
                                        variant="bordered"
                                        label="Name"
                                        value={data.name}
                                        onChange={(e) => setData("name", e.target.value)}
                                    />
                                    <InputError message={errors.name} className="mt-2"/>

                                    <Input
                                        variant="bordered"
                                        label="Username"
                                        value={data.username}
                                        onChange={(e) => setData("username", e.target.value)}
                                    />
                                    <InputError message={errors.username} className="mt-2"/>

                                    <PasswordInput
                                        variant="bordered"
                                        label="Password"
                                        value={data.password}
                                        onChange={(e) => setData("password", e.target.value)}
                                    />
                                    <InputError message={errors.password} className="mt-2"/>

                                    <Input
                                        variant="bordered"
                                        label="URL"
                                        value={data.url}
                                        onChange={(e) => setData("url", e.target.value)}
                                    />
                                    <InputError message={errors.url} className="mt-2"/>

                                    <Textarea
                                        variant="bordered"
                                        label="Note"
                                        value={data.note}
                                        onChange={(e) => setData("note", e.target.value)}
                                    />
                                    <InputError message={errors.note} className="mt-2"/>

                                    {isDirty && (
                                        <Button type="submit" color="primary" isDisabled={processing}>
                                            Save
                                        </Button>
                                    )}
                                </div>
                            </form>
                        </section>
                    </div>
                </div>
            </div>

        </AuthenticatedLayout>
    );
}
