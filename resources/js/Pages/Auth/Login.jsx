import InputError from "@/Components/InputError";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import PasswordInput from "@/Components/Form/PasswordInput";
import { Input, Checkbox, Button } from "@nextui-org/react";
import { Link as InertiaLink } from "@inertiajs/react";
import { Link as NextUILink } from "@nextui-org/react";
import { Input, Link, Checkbox, Button } from "@nextui-org/react";
import SystemAlert from "@/Components/SystemAlert.jsx";

export default function Login({ status, canResetPassword }) {
    const { data, setData, post, processing, errors, reset } = useForm({
        email: "",
        password: "",
        remember: false,
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("login"), {
            onFinish: () => reset("password"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Log in" />

            <SystemAlert />

            <form onSubmit={submit}>
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

                    <InputError message={errors.email} className="mt-2" />
                </div>

                <div className="mt-4">
                    <PasswordInput
                        label="Password"
                        variant="bordered"
                        name="password"
                        value={data.password}
                        autoComplete="current-password"
                        onChange={(e) => setData("password", e.target.value)}
                    />

                    <InputError message={errors.password} className="mt-2" />
                </div>

                <div className="mt-4 block">
                    <div className="flex flex-col gap-2">
                        <Checkbox
                            isSelected={data.remember}
                            onChange={(e) =>
                                setData("remember", e.target.checked)
                            }
                        >
                            Remember me
                        </Checkbox>
                    </div>
                </div>

                <div className="mt-4 flex items-center justify-end">
                    {canResetPassword && (
                        <InertiaLink
                            href={route("password.request")}
                            className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Forgot your password?
                        </InertiaLink>
                    )}
                    <p className="text-md text-gray-600">
                        Don't have an account?{" "}
                        <NextUILink
                            as={InertiaLink}
                            href="/register"
                            underline="hover"
                            className="mr-4"
                        >
                            Register here
                        </NextUILink>
                    </p>
                    <Button type="submit" color="primary" variant="flat">
                        LOG IN
                    </Button>
                </div>
            </form>
        </GuestLayout>
    );
}
