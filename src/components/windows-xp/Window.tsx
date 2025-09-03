'use client';

import React, { useState, useRef, useEffect } from 'react';
import { X, Minus, Square } from 'lucide-react';

interface WindowProps {
    title: string;
    children: React.ReactNode;
    width?: number;
    height?: number;
    x?: number;
    y?: number;
    onClose?: () => void;
    onMinimize?: () => void;
    onMaximize?: () => void;
    resizable?: boolean;
    className?: string;
}

export default function Window({
    title,
    children,
    width = 600,
    height = 400,
    x = 100,
    y = 100,
    onClose,
    onMinimize,
    onMaximize,
    resizable = true,
    className = '',
}: WindowProps) {
    const [position, setPosition] = useState({ x, y });
    const [size, setSize] = useState({ width, height });
    const [isDragging, setIsDragging] = useState(false);
    const [isResizing, setIsResizing] = useState(false);
    const [dragStart, setDragStart] = useState({ x: 0, y: 0 });
    const [isMaximized, setIsMaximized] = useState(false);
    const [originalSize, setOriginalSize] = useState({ width, height });
    const [originalPosition, setOriginalPosition] = useState({ x, y });

    const windowRef = useRef<HTMLDivElement>(null);

    const handleMouseDown = (e: React.MouseEvent) => {
        if (e.target === e.currentTarget || (e.target as HTMLElement).classList.contains('xp-titlebar')) {
            setIsDragging(true);
            setDragStart({
                x: e.clientX - position.x,
                y: e.clientY - position.y,
            });
        }
    };

    const handleMouseMove = (e: MouseEvent) => {
        if (isDragging && !isMaximized) {
            const newX = e.clientX - dragStart.x;
            const newY = e.clientY - dragStart.y;

            // Keep window within viewport bounds
            const maxX = window.innerWidth - size.width;
            const maxY = window.innerHeight - size.height - 30; // Account for taskbar

            setPosition({
                x: Math.max(0, Math.min(newX, maxX)),
                y: Math.max(0, Math.min(newY, maxY)),
            });
        }
    };

    const handleMouseUp = () => {
        setIsDragging(false);
        setIsResizing(false);
    };

    const handleMaximize = () => {
        if (isMaximized) {
            // Restore
            setSize(originalSize);
            setPosition(originalPosition);
            setIsMaximized(false);
        } else {
            // Maximize
            setOriginalSize(size);
            setOriginalPosition(position);
            setSize({
                width: window.innerWidth,
                height: window.innerHeight - 30, // Account for taskbar
            });
            setPosition({ x: 0, y: 0 });
            setIsMaximized(true);
        }
        onMaximize?.();
    };

    useEffect(() => {
        if (isDragging || isResizing) {
            document.addEventListener('mousemove', handleMouseMove);
            document.addEventListener('mouseup', handleMouseUp);
            return () => {
                document.removeEventListener('mousemove', handleMouseMove);
                document.removeEventListener('mouseup', handleMouseUp);
            };
        }
    }, [isDragging, isResizing, dragStart, position, size]);

    return (
        <div
            ref={windowRef}
            className={`xp-window fixed ${className}`}
            style={{
                left: position.x,
                top: position.y,
                width: size.width,
                height: size.height,
                zIndex: 100,
            }}
            onMouseDown={handleMouseDown}
        >
            {/* Title Bar */}
            <div className="xp-titlebar">
                <span>{title}</span>
                <div className="xp-titlebar-buttons">
                    <button
                        className="xp-titlebar-button"
                        onClick={onMinimize}
                        title="Minimize"
                    >
                        <Minus size={8} />
                    </button>
                    <button
                        className="xp-titlebar-button"
                        onClick={handleMaximize}
                        title={isMaximized ? "Restore" : "Maximize"}
                    >
                        <Square size={8} />
                    </button>
                    <button
                        className="xp-titlebar-button"
                        onClick={onClose}
                        title="Close"
                    >
                        <X size={8} />
                    </button>
                </div>
            </div>

            {/* Window Content */}
            <div className="flex-1 overflow-auto" style={{ height: size.height - 20 }}>
                {children}
            </div>
        </div>
    );
}
