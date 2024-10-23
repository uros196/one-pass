import { router } from "@inertiajs/react";
import { useEffect } from "react";

export default function UserActivityCheck() {

    // check the user last activity
    const checkActivity = () => {
        fetch(route('activity.check'), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
            }
        })
        .then(response => {
            response.json().then((data) => {
                if (response.status === 401) {
                    router.visit(data.redirect);
                }
            })
        });
    }

    useEffect(() => {

        // check the user activity when the window is in the focus
        const handleVisibilityChange = () => {
            if (document.visibilityState === 'visible') {
                checkActivity();
            }
        };

        window.addEventListener('focus', checkActivity);
        document.addEventListener('visibilitychange', handleVisibilityChange);

        return () => {
            window.removeEventListener('focus', checkActivity);
            document.removeEventListener('visibilitychange', handleVisibilityChange);
        };

    }, []);

    return null;
}
