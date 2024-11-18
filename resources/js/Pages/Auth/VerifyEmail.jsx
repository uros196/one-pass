import PrimaryButton from "@/Components/PrimaryButton";
import GuestLayout from "@/Layouts/GuestLayout";
import { Head, Link, useForm } from "@inertiajs/react";
import { Input, Button } from "@nextui-org/react";
import { Link as InertiaLink } from '@inertiajs/react';
import { Link as NextUILink } from '@nextui-org/react';
export default function VerifyEmail({ status }) {
    const { post, processing } = useForm({});

    const submit = (e) => {
        e.preventDefault();

        post(route("verification.send"));
    };

    return (
        <GuestLayout>
            <Head title="Email Verification" />

            <div className="mb-4 text-sm text-gray-600">
                Thanks for signing up! Before getting started, could you verify
                your email address by clicking on the link we just emailed to
                you? If you didn't receive the email, we will gladly send you
                another.
            </div>

            {status === "verification-link-sent" && (
                <div className="mb-4 text-sm font-medium text-green-600">
                    A new verification link has been sent to the email address
                    you provided during registration.
                </div>
            )}

            <form onSubmit={submit}>
                <div className="mt-4 flex items-center justify-between">
                  
                    <Button
                        className="ms-4"
                        isDisabled={processing}
                        color="primary"
                        variant="flat"
                        type="submit"
                    >
                        Resend Verification Email
                    </Button>
                   
                    <NextUILink
                        as={InertiaLink} 
                        href={route("logout")}
                        method="post" 
                        underline="hover"
                        className="mr-4"                        
                        css={{ display: "inline-block" }} 
                    >
                        Log Out
                    </NextUILink>
                </div>
            </form>
        </GuestLayout>
    );
}
