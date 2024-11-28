import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";
import {Button, DatePicker, Input, Textarea} from "@nextui-org/react";
import {useForm} from "@inertiajs/react";
import PasswordInput from "@/Components/Form/PasswordInput.jsx";
import {useEncrypted} from "@/Components/Encryption/WorkWithToken.jsx";
import InputMask from 'react-input-mask';
import useFormConstruct from "@/Components/Form/FormConstruct.jsx";

const LoginData = () => {
    const { encryptedData } = useEncrypted();

    const form = useForm({
        name: '',
        username: '',
        password: '',
        url: '',
        note: ''
    })
    const { post, reset, processing, isDirty } = form;

    const { initInput } = useFormConstruct(form);

    const saveLogin = (e) => {
        e.preventDefault();

        encryptedData((options) => {
            post(route('sensitive-data.store', {type: 'login'}), {
                preserveScroll: true,
                onSuccess: () => reset(),
                ...options
            })
        });
    };

    return (
        <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <section className="space-y-6">
                <header>
                    <h2 className="text-lg font-medium text-gray-900">
                        Login
                    </h2>

                    <p className="mt-1 text-sm text-gray-600">
                        Testing creating new Login data
                    </p>
                </header>

                <form onSubmit={saveLogin} className="mt-6 space-y-6">
                    <div className="w-full flex flex-col gap-4">

                        <Input {...initInput('name')} label="Name" isRequired={true} />
                        <Input {...initInput('username')} label="Username" />
                        <PasswordInput {...initInput('password')} label="Password" />
                        <Input {...initInput('url')} label="URL" />
                        <Textarea {...initInput('note')} label="Note" />

                        {isDirty && (
                            <Button type="submit" color="primary" isDisabled={processing}>
                                Save
                            </Button>
                        )}
                    </div>
                </form>
            </section>
        </div>
    );
};

const BankCard = () => {
    const { encryptedData } = useEncrypted();

    const form = useForm({
        name: '',
        number: '',
        expire_date: '',
        cvc: '',
        pin: '',
        holder_name: '',
        note: ''
    })
    const { post, reset, data, setData, errors, processing, isDirty } = form;

    const { error, initInput } = useFormConstruct(form);

    const saveCard = (e) => {
        e.preventDefault();

        encryptedData((options) => {
            post(route('sensitive-data.store', {type: 'bank-card'}), {
                preserveScroll: true,
                onSuccess: () => reset(),
                ...options
            })
        });
    };

    return (
        <div className="bg-white p-4 shadow sm:rounded-lg sm:p-8">
            <section className="space-y-6">
                <header>
                    <h2 className="text-lg font-medium text-gray-900">
                        Bank Card
                    </h2>

                    <p className="mt-1 text-sm text-gray-600">
                        Testing creating a new Bank Card
                    </p>
                </header>

                <form onSubmit={saveCard} className="mt-6 space-y-6">
                    <Input {...initInput('name')} label="Name" isRequired={true} />

                    <InputMask
                        mask="9999  9999  9999  9999"
                        maskChar=" "
                        value={data.number}
                        onChange={(e) => setData("number", e.target.value)}
                    >
                        {(inputProps) => (
                            <Input
                                {...inputProps}
                                {...error('number')}
                                variant="bordered"
                                label="Card Number"
                                isRequired={true}
                            />
                        )}
                    </InputMask>

                    {/*<DatePicker*/}
                    {/*    variant="bordered"*/}
                    {/*    label="Expiration date"*/}
                    {/*    // value={data.expire_date}*/}
                    {/*    size="sm"*/}
                    {/*    isInvalid={!!errors.expire_date}*/}
                    {/*    errorMessage={errors.expire_date}*/}
                    {/*    // onChange={(e) => setData("expire_date", e.target.value)}*/}
                    {/*/>*/}
                    <Input {...initInput('expire_date')} label="Expiration date" />

                    <PasswordInput {...initInput('cvc')} label="CVC" />
                    <PasswordInput {...initInput('pin')} label="Card pin" />
                    <Input {...initInput('holder_name')} label="Cardholder name" />
                    <Textarea {...initInput('note')} label="Note" />

                    {isDirty && (
                        <Button type="submit" color="primary" isDisabled={processing}>
                            Save
                        </Button>
                    )}
                </form>
            </section>
        </div>
    );
}

export default function TestEncryptionDataCreating() {
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
                    <LoginData />
                    <BankCard />
                </div>
            </div>
        </AuthenticatedLayout>
    );
}
