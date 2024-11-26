import {createContext, useContext, useEffect, useRef, useState} from "react";
import {Button, Modal, ModalBody, ModalContent, ModalFooter, ModalHeader, useDisclosure} from "@nextui-org/react";
import {useForm} from "@inertiajs/react";
import PasswordInput from "@/Components/Form/PasswordInput.jsx";
import InputError from "@/Components/InputError.jsx";

const EncryptedDataContext = createContext();

export const useEncrypted = () => {
    return useContext(EncryptedDataContext);
};

/**
 * This provider allows us to manage encrypted data more easily.
 * It offers a solution for verifying the Master Password when accessing sensitive data
 * (when such data is being read or saved).
 *
 * Flow:
 *  - User submits 'create' form, for example (without the valid token)
 *  - Confirmation Master Password modal appears
 *  - After successful confirmation, modal is closing, and the previous request is submitting again
 *    automatically (now, with the valid token)
 *
 * @param children
 * @returns {JSX.Element}
 * @constructor
 */
export const EncryptedDataProvider = ({children}) => {
    // short-life token that will be used for encrypting/decrypting data that uses Challenge Encryption method
    // as long as this token is valid, the user will not be asked to confirm their Master Password again
    const [token, setToken] = useState('token');

    // state that controls modal for Master Password confirmation
    const [isPromptedMasterPassword, promptMasterPassword] = useState(false);

    // holds the callback for re-submission after token failure
    const [submitAgain, setSubmitAgain] = useState({});

    // this method defines everything you need for successful request submission
    // it defines the necessary header (with the token) and error handler if the token is invalid
    // 'callback' represent whatever you need to do (ajax call, form submission, ...)
    // you only need to integrate the headers and onError into your callback for it to function properly
    const encryptedData = (callback) => {
        callback({
            headers: {
                'X-ENCRYPT-TOKEN': token
            },
            onError: (error) => {
                // check for validation error (invalid token)
                if (error.encryption_token) {
                    // save callback for the re-submission after user confirms its Master Password
                    setSubmitAgain({
                        callback: callback
                    });

                    // require opening the Master Password confirmation modal
                    promptMasterPassword(true);
                }
            }
        });
    };

    // watch token changes and re-submit previous request
    useEffect(() => {
        if (token !== 'token') {
            encryptedData(submitAgain.callback);
            setSubmitAgain({});
        }
    }, [token]);

    return (
        <EncryptedDataContext.Provider value={{ setToken, isPromptedMasterPassword, promptMasterPassword, encryptedData }}>
            {children}
            <ConfirmPasswordModal />
        </EncryptedDataContext.Provider>
    );
};

/**
 * Confirmation modal that will appear when a user is requested to confirm its Master Password.
 * Modal will be opened automatically when the token is invalid.
 *
 * @returns {JSX.Element}
 * @constructor
 */
const ConfirmPasswordModal = () => {
    const passwordInput = useRef();
    const form = useForm({master_password: ''});

    // handle NextUI modal events
    const { isOpen, onOpen, onOpenChange, onClose } = useDisclosure();

    // use EncryptedDataContext to handle the token and the modal
    const { setToken, isPromptedMasterPassword, promptMasterPassword } = useEncrypted();

    // open modal if prompted master password is requested
    useEffect(() => {
        if (isPromptedMasterPassword) {
            onOpen();
        }
    }, [isPromptedMasterPassword]);

    // request from the server to generate the token
    const getEncryptionToken = (e) => {
        e.preventDefault();

        form.post(route('generate-encryption-token'), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                setToken(page.props.flash.token);
                closeModal();
            },
            onError: () => {
                passwordInput.current.focus();
                form.reset('master_password');
            }
        });
    };

    const closeModal = () => {
        form.reset('master_password');
        form.clearErrors();
        onClose();
        promptMasterPassword(false);
    };

    return (
        <Modal backdrop="blur" size="2xl" onClose={closeModal} isOpen={isOpen} onOpenChange={onOpenChange}>
            <ModalContent>
                {(closeModal) => (
                    <>
                        <ModalHeader>
                            Confirm your password in order to continue
                        </ModalHeader>
                        <ModalBody>
                            <form onSubmit={getEncryptionToken} id="get-token">
                                <PasswordInput
                                    autoFocus
                                    variant="bordered"
                                    label="Password"
                                    placeholder="Enter your master password"
                                    value={form.data.master_password}
                                    onChange={(e) => form.setData("master_password", e.target.value)}
                                    ref={passwordInput}
                                />
                                <InputError message={form.errors.master_password} className="mt-2" />
                            </form>
                        </ModalBody>
                        <ModalFooter>
                            <Button color="default" variant="flat" onPress={closeModal}>
                                Close
                            </Button>
                            <Button color="primary" form="get-token" isDisabled={form.processing || !form.isDirty} type="submit">
                                Submit
                            </Button>
                        </ModalFooter>
                    </>
                )}
            </ModalContent>
        </Modal>
    );
};
