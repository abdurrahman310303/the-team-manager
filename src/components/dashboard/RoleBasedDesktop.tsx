'use client';

import React from 'react';
import {
    Users,
    DollarSign,
    Briefcase,
    FileText,
    Folder,
    Recycle,
    Shield,
    Code,
    Target,
    Calendar,
    Settings,
    HelpCircle
} from 'lucide-react';
import { User } from '@/types/auth';

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

interface RoleBasedDesktopProps {
    user: User;
    onIconClick: (iconId: string) => void;
    selectedIcon?: string;
}

export default function RoleBasedDesktop({ user, onIconClick, selectedIcon }: RoleBasedDesktopProps) {
    const getDesktopIcons = () => {
        const commonIcons = [
            { id: 'documents', label: 'Documents', icon: <Folder size={24} color="white" /> },
            { id: 'recycle', label: 'Recycle Bin', icon: <Recycle size={24} color="white" /> },
            { id: 'settings', label: 'Settings', icon: <Settings size={24} color="white" /> },
            { id: 'help', label: 'Help', icon: <HelpCircle size={24} color="white" /> }
        ];

        switch (user.role) {
            case 'admin':
                return [
                    { id: 'admin', label: 'User Management', icon: <Shield size={24} color="white" /> },
                    { id: 'team', label: 'Team Overview', icon: <Users size={24} color="white" /> },
                    { id: 'finance', label: 'Financial Overview', icon: <DollarSign size={24} color="white" /> },
                    { id: 'upwork', label: 'Upwork Overview', icon: <Briefcase size={24} color="white" /> },
                    { id: 'linkedin', label: 'LinkedIn Overview', icon: <FileText size={24} color="white" /> },
                    ...commonIcons
                ];

            case 'investor':
                return [
                    { id: 'investor', label: 'Fund Management', icon: <DollarSign size={24} color="white" /> },
                    { id: 'team', label: 'Team Status', icon: <Users size={24} color="white" /> },
                    { id: 'finance', label: 'Financial Reports', icon: <FileText size={24} color="white" /> },
                    ...commonIcons
                ];

            case 'business_developer':
                return [
                    { id: 'bd', label: 'BD Dashboard', icon: <Target size={24} color="white" /> },
                    { id: 'upwork', label: 'Upwork Bidding', icon: <Briefcase size={24} color="white" /> },
                    { id: 'linkedin', label: 'LinkedIn Optimization', icon: <FileText size={24} color="white" /> },
                    { id: 'team', label: 'Team Status', icon: <Users size={24} color="white" /> },
                    ...commonIcons
                ];

            case 'developer':
                return [
                    { id: 'developer', label: 'My Projects', icon: <Code size={24} color="white" /> },
                    { id: 'meetings', label: 'Meetings', icon: <Calendar size={24} color="white" /> },
                    { id: 'team', label: 'Team Status', icon: <Users size={24} color="white" /> },
                    ...commonIcons
                ];

            default:
                return commonIcons;
        }
    };

    const desktopIcons = getDesktopIcons();

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
