import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.jsx";

export default function ExampleText() {
    return (
        <AuthenticatedLayout
            header={
                <h2 className="text-xl font-semibold leading-tight text-gray-800">
                    Profile
                </h2>
            }
        >
            <div className="py-12">
                <div className="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">
                    Example Text
                </div>
            </div>
        </AuthenticatedLayout>
)
}
