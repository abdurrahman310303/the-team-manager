'use client';

import React from 'react';
import {
    Users,
    DollarSign,
    Briefcase,
    FileText,
    Folder,
    Recycle
} from 'lucide-react';

interface DesktopIconProps {
    icon: React.ReactNode;
    label: string;
    onClick: () => void;
    isSelected?: boolean;
}

function DesktopIcon({ icon, label, onClick, isSelected = false }: DesktopIconProps) {
    return (
        <div
            className={`xp-desktop-icon ${isSelected ? 'selected' : ''}`}
            onClick={onClick}
        >
            <div className="w-8 h-8 flex items-center justify-center mb-1">
                {icon}
            </div>
            <span className="text-center leading-tight">{label}</span>
        </div>
    );
}

interface DesktopProps {
    onIconClick: (iconId: string) => void;
    selectedIcon?: string;
}

export default function Desktop({ onIconClick, selectedIcon }: DesktopProps) {
    const desktopIcons = [
        { id: 'team', label: 'Team Members', icon: <Users size={24} color="white" /> },
        { id: 'finance', label: 'Financial Tracking', icon: <DollarSign size={24} color="white" /> },
        { id: 'upwork', label: 'Upwork Bidding', icon: <Briefcase size={24} color="white" /> },
        { id: 'linkedin', label: 'LinkedIn Optimization', icon: <FileText size={24} color="white" /> },
        { id: 'documents', label: 'Documents', icon: <Folder size={24} color="white" /> },
        { id: 'recycle', label: 'Recycle Bin', icon: <Recycle size={24} color="white" /> },
    ];

    return (
        <div className="absolute inset-0 pb-8 p-4">
            <div className="grid grid-cols-8 gap-4 h-full">
                {desktopIcons.map((icon) => (
                    <DesktopIcon
                        key={icon.id}
                        icon={icon.icon}
                        label={icon.label}
                        onClick={() => onIconClick(icon.id)}
                        isSelected={selectedIcon === icon.id}
                    />
                ))}
            </div>
        </div>
    );
}
