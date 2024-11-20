import GuestLayout from "@/Layouts/GuestLayout";
import { Head, useForm } from "@inertiajs/react";
import InputLabel from "@/Components/InputLabel";
import TextInput from "@/Components/TextInput";
import InputError from "@/Components/InputError";
import PrimaryButton from "@/Components/PrimaryButton";
import PasswordInput from "@/Components/Form/PasswordInput";
import { Input, Button } from "@nextui-org/react";

export default function UnlockAccount({ hash }) {
    const { data, setData, post, reset, processing, errors } = useForm({
        hash: hash,
        password: "",
    });

    const submit = (e) => {
        e.preventDefault();

        post(route("unlock-account.verify"), {
            onFinish: () => reset("password"),
        });
    };

    return (
        <GuestLayout>
            <Head title="Unlock your account" />

            <div className="mb-4 text-sm text-gray-600">
                Confirm the password in order to unlock your account.
            </div>

            <form onSubmit={submit}>
                <div className="mt-4">
                 
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
                    
                    <Button
      className="ms-4"
      isDisabled={processing}  
      color="primary"
      variant="flat" 
      type="submit"
    >
      SAVE
    </Button>
                   
                </div>
            </form>
        </GuestLayout>
    );
}
