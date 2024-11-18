import InputError from "@/Components/InputError";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import { Input, Link } from "@nextui-org/react";
import PasswordInput from "@/Components/Form/PasswordInput";
import { Button } from "@nextui-org/button";
import SystemAlert from "@/Components/SystemAlert.jsx";

export default function Register() {
    const { data, setData, post, processing, errors, reset } = useForm({
        name: "",
        email: "",
        password: "",
        password_confirmation: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("register"), {
            onFinish: () => reset("password", "password_confirmation"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Register" />

            <SystemAlert />

            <form onSubmit={submit}>
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
                    <InputError message={errors.name} className="mt-2" />
                </div>

                <div className="mt-4">

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

                <div className="mt-4">

                    <PasswordInput
                        label="Password"
                        variant="bordered"
                        name="Confirm password"
                        value={data.password_confirmation}
                        autoComplete="current-password"
                        onChange={(e) =>
                            setData("password_confirmation", e.target.value)
                        }
                    />
                    <InputError
                        message={errors.password_confirmation}
                        className="mt-2"
                    />
                </div>

                <div className="mt-4 flex items-center justify-end">

                    <Link
                        href="http://one-pass.test/login"
                        underline="always"
                        className="mr-4"
                    >
                        Already registered?
                    </Link>

                    {/* <div className="mt-2 flex items-center justify-center"> */}
                    <Button type="submit" color="primary" variant="flat">
                        REGISTER
                    </Button>
                    {/* </div> */}
                </div>
            </form>
        </GuestLayout>
    );
}
