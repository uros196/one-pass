import {usePage} from "@inertiajs/react";

export default function SystemAlert() {
    const { system_alert } = usePage().props;

    const alertType = () => {
        let color;

        switch (system_alert.status) {
            case 'info':
                color = 'text-blue-400';
                break;

            case 'danger':
                color = 'text-red-600';
                break;

            case 'success':
                color = 'text-green-600';
                break;

            case 'warning':
                color = 'text-orange-400';
                break;
        }

        return color;
    };

    return system_alert.message && (
        <div className={'mb-4 text-sm font-medium ' + alertType()}>
            {system_alert.message}
        </div>
    );
}
