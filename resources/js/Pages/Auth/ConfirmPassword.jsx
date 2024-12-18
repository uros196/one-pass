import InputError from '@/Components/InputError';
import InputLabel from '@/Components/InputLabel';
import PrimaryButton from '@/Components/PrimaryButton';
import TextInput from '@/Components/TextInput';
import GuestLayout from '@/Layouts/GuestLayout';
import { Head, useForm } from '@inertiajs/react';
import PasswordInput from "@/Components/Form/PasswordInput";
import { Button } from "@nextui-org/button";
import SystemAlert from "@/Components/SystemAlert.jsx";
import { Link as InertiaLink } from '@inertiajs/react';
import { Link as NextUILink } from '@nextui-org/react';

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({
        password: '',
    });

    const submit = (e) => {
        e.preventDefault();

        post(route('password.confirm'), {
            onFinish: () => reset('password'),
        });
    };

    return (
        <GuestLayout>
            <Head title="Confirm Password" />

            <SystemAlert />

            <div className="mb-4 text-sm text-gray-600">
                This is a secure area of the application. Please confirm your
                password before continuing.
            </div>

            <form onSubmit={submit}>
                <div className="mt-4">
                    {/* <InputLabel htmlFor="password" value="Password" />

                    <TextInput
                        id="password"
                        type="password"
                        name="password"
                        value={data.password}
                        className="mt-1 block w-full"
                        isFocused={true}
                        onChange={(e) => setData('password', e.target.value)}
                    /> */}
                    


                    <PasswordInput
                        label="Password"
                        variant="bordered"
                        name="password"
                        value={data.password}
                        autoComplete="current-password"
                        onChange={(e) => setData("password", e.target.value)}
                        autoFocus
                    />
                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4 flex items-center justify-end">
                    {/* <PrimaryButton className="ms-4" disabled={processing}>
                        Confirm
                    </PrimaryButton> */}
                     <Button type="submit" color="primary" variant="flat" isDisabled={processing}>
                        CONFIRM
                    </Button>
                    <NextUILink
                        as={InertiaLink}
                        href={route("logout")}
                        method="post"
                        underline="hover"
                        className="ml-4"
                        css={{ display: "inline-block" }}
                    >
                        Log Out
                    </NextUILink>
                </div>
            </form>
        </GuestLayout>
    );
}
