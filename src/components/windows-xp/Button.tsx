'use client';

import React from 'react';

interface ButtonProps {
    children: React.ReactNode;
    onClick?: () => void;
    disabled?: boolean;
    className?: string;
    type?: 'button' | 'submit' | 'reset';
}

export default function Button({
    children,
    onClick,
    disabled = false,
    className = '',
    type = 'button',
}: ButtonProps) {
    return (
        <button
            type={type}
            className={`xp-button ${className}`}
            onClick={onClick}
            disabled={disabled}
        >
            {children}
        </button>
    );
}
