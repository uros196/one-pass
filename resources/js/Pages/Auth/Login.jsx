import InputError from "@/Components/InputError";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import PasswordInput from "@/Components/Form/PasswordInput";
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
                        <Link
                            href={route("password.request")}
                            className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                        >
                            Forgot your password?
                        </Link>
                    )}
                    <p className="text-md text-gray-600">
                        Don't have an account?{" "}
                        <Link
                        href="http://one-pass.test/register"
                        underline="hover"
                        className="mr-4"
                    >

                    Register here
                    </Link>


                    </p>
                    <Button type="submit" color="primary" variant="flat">
                        LOG IN
                    </Button>
                </div>
            </form>
        </GuestLayout>
    );
}
