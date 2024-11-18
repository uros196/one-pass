import InputError from "@/Components/InputError";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import { Input } from "@nextui-org/react";
import { Link as InertiaLink } from "@inertiajs/react";
import { Link as NextUILink } from "@nextui-org/react";
import PasswordInput from "@/Components/Form/PasswordInput";
import { Button } from "@nextui-org/button";

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
                    <NextUILink
                        as={InertiaLink}
                        href="/login"
                        underline="always"
                        className="mr-4"
                    >
                        Already registered?
                    </NextUILink>

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
