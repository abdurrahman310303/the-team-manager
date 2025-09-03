'use client';

import React, { useState } from 'react';
import { Play, Clock } from 'lucide-react';

interface TaskbarProps {
    onStartMenuClick: () => void;
    openWindows: Array<{
        id: string;
        title: string;
        isActive: boolean;
        onClick: () => void;
    }>;
}

export default function Taskbar({ onStartMenuClick, openWindows }: TaskbarProps) {
    const [currentTime, setCurrentTime] = useState(new Date());

    React.useEffect(() => {
        const timer = setInterval(() => {
            setCurrentTime(new Date());
        }, 1000);

        return () => clearInterval(timer);
    }, []);

    const formatTime = (date: Date) => {
        return date.toLocaleTimeString('en-US', {
            hour: '2-digit',
            minute: '2-digit',
            hour12: true,
        });
    };

    return (
        <div className="xp-taskbar">
            {/* Start Button */}
            <button
                className="xp-start-button"
                onClick={onStartMenuClick}
            >
                <Play size={12} />
                Start
            </button>

            {/* Taskbar Items */}
            <div className="flex-1 flex items-center gap-1 px-2">
                {openWindows.map((window) => (
                    <button
                        key={window.id}
                        className={`xp-button text-xs px-3 ${window.isActive ? 'bg-blue-500 text-white' : ''
                            }`}
                        onClick={window.onClick}
                    >
                        {window.title}
                    </button>
                ))}
            </div>

            {/* System Tray */}
            <div className="flex items-center gap-2 px-2">
                <div className="flex items-center gap-1 text-xs">
                    <Clock size={12} />
                    {formatTime(currentTime)}
                </div>
            </div>
        </div>
    );
}
