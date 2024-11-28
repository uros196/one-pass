/**
 * Function for quicker-defining properties that always are the same.
 *
 * @param useForm
 */
export default function useFormConstruct(useForm) {
    // set error message handler
    const error = (name) => {
        return {
            isInvalid: !!useForm.errors[name],
            errorMessage: useForm.errors[name],
        }
    }

    // handle all about value (watch changes)
    const value = (name) => {
        return {
            value: useForm.data[name],
            onValueChange: (value) => useForm.setData(name, value)
        }
    }

    // init the most common input (using properties defines above)
    const initInput = (name) => {
        return {
            variant: "bordered",
            size: "sm",
            ...value(name),
            ...error(name)
        }
    }

    // return all available functions
    return {
        error: error,
        value: value,
        initInput: initInput
    };
}
