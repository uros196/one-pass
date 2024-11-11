import React from "react";
import { Input } from "@nextui-org/react";
import { forwardRef } from 'react';

import { EyeFilledIcon } from "@/Components/Icons/EyeFilledIcon";
import { EyeSlashFilledIcon } from "@/Components/Icons/EyeSlashFilledIcon";

export default forwardRef(function PasswordInput({...props}, ref) {
    const [isVisible, setIsVisible] = React.useState(false);

    const toggleVisibility = () => setIsVisible(!isVisible);
  
    return (
      <Input
        {...props}
        endContent={
          <button className="focus:outline-none" type="button" onClick={toggleVisibility} aria-label="toggle password visibility">
            {isVisible ? (
              <EyeSlashFilledIcon className="text-2xl text-default-400 pointer-events-none" />
            ) : (
              <EyeFilledIcon className="text-2xl text-default-400 pointer-events-none" />
            )}
          </button>
        }
        type={isVisible ? "text" : "password"}
        ref={ref}
      />
    );
});

