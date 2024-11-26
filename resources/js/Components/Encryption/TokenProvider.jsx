import {createContext, useContext, useEffect, useRef, useState} from "react";
import {Button, Modal, ModalBody, ModalContent, ModalFooter, ModalHeader, useDisclosure} from "@nextui-org/react";
import {useForm} from "@inertiajs/react";
import PasswordInput from "@/Components/Form/PasswordInput.jsx";
import InputError from "@/Components/InputError.jsx";

const EncryptedDataContext = createContext();

export const useEncrypted = () => {
    return useContext(EncryptedDataContext);
};

export const EncryptedDataProvider = ({children}) => {
    const [token, setToken] = useState('token');
    const [promptMasterPassword, setPromptMasterPassword] = useState(false);
    const [confirmedPassword, setConfirmedPassword] = useState(false);
    const [submitAgain, setSubmitAgain] = useState({});

    const encryptedData = (method, url, options = {}) => {
        method(url, {
            ...options,
            headers: {
                'X-ENCRYPT-TOKEN': token
            },
            onError: (error) => {
                if (error.encryption_token) {
                    setSubmitAgain({
                        method: method,
                        url: url,
                        options: options
                    });

                    setConfirmedPassword(false);
                    setPromptMasterPassword(true);
                }
            },
        });
    };

    const confirmedMasterPassword = (token) => {
        setToken(token);
        setConfirmedPassword(true);
    }

    useEffect(() => {
        if (confirmedPassword) {
            encryptedData(submitAgain.method, submitAgain.url, submitAgain.options);
            setSubmitAgain({});
        }
    }, [confirmedPassword]);

    return (
        <EncryptedDataContext.Provider value={{ promptMasterPassword, setPromptMasterPassword, encryptedData, confirmedMasterPassword }}>
            {children}
            <ConfirmPasswordModal />
        </EncryptedDataContext.Provider>
    );
};

const ConfirmPasswordModal = () => {
    const passwordInput = useRef();
    const form = useForm({master_password: ''});

    const { isOpen, onOpen, onOpenChange, onClose } = useDisclosure();

    const { confirmedMasterPassword, promptMasterPassword, setPromptMasterPassword } = useEncrypted();

    // open modal if prompted master password is requested
    useEffect(() => {
        if (promptMasterPassword) {
            onOpen();
        }
    }, [promptMasterPassword]);

    const getEncryptionToken = (e) => {
        e.preventDefault();

        form.post(route('generate-encryption-token'), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: (page) => {
                confirmedMasterPassword(page.props.flash.token);
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
        setPromptMasterPassword(false);
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
