import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm } from '@inertiajs/react';
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";

export default function UnlockAccount({hash}) {
    const { data, setData, post, reset, processing, errors } = useForm({
        hash: hash,
        password: '',
    })

    const submit = (e) => {
        e.preventDefault();

        post(route('unlock-account.verify'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Unlock your account"/>

            <div className="mb-4 text-sm text-gray-600">
                Confirm the password in order to unlock your account.
            </div>

            <form onSubmit={submit}>
                <div className="mt-4">
                    <InputLabel htmlFor="password" value="Password"/>

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        onChange={(e) => setData('password', e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2"/>
                </div>

                <div className="mt-4 flex items-center justify-end">
                    <PrimaryButton className="ms-4" disabled={processing}>
                        Unlock
                    </PrimaryButton>
                </div>
            </form>
        </GuestLayout>
    );
}
