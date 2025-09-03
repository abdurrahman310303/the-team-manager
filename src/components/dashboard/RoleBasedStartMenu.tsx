'use client';

import React from 'react';
import {
    Users,
    DollarSign,
    Briefcase,
    FileText,
    Settings,
    HelpCircle,
    LogOut,
    Shield,
    Code,
    Target,
    Calendar
} from 'lucide-react';
import { User } from '@/types/auth';

interface StartMenuProps {
    isOpen: boolean;
    onClose: () => void;
    onMenuItemClick: (item: string) => void;
    user: User;
}

export default function StartMenu({ isOpen, onClose, onMenuItemClick, user }: StartMenuProps) {
    if (!isOpen) return null;

    const getMenuItems = () => {
        const commonItems = [
            { id: 'settings', label: 'Settings', icon: Settings },
            { id: 'help', label: 'Help', icon: HelpCircle },
            { id: 'logout', label: 'Logout', icon: LogOut },
        ];

        switch (user.role) {
            case 'admin':
                return [
                    { id: 'admin', label: 'User Management', icon: Shield },
                    { id: 'team', label: 'Team Overview', icon: Users },
                    { id: 'finance', label: 'Financial Overview', icon: DollarSign },
                    { id: 'upwork', label: 'Upwork Overview', icon: Briefcase },
                    { id: 'linkedin', label: 'LinkedIn Overview', icon: FileText },
                    ...commonItems
                ];

            case 'investor':
                return [
                    { id: 'investor', label: 'Fund Management', icon: DollarSign },
                    { id: 'team', label: 'Team Status', icon: Users },
                    { id: 'finance', label: 'Financial Reports', icon: FileText },
                    ...commonItems
                ];

            case 'business_developer':
                return [
                    { id: 'bd', label: 'BD Dashboard', icon: Target },
                    { id: 'upwork', label: 'Upwork Bidding', icon: Briefcase },
                    { id: 'linkedin', label: 'LinkedIn Optimization', icon: FileText },
                    { id: 'team', label: 'Team Status', icon: Users },
                    ...commonItems
                ];

            case 'developer':
                return [
                    { id: 'developer', label: 'My Projects', icon: Code },
                    { id: 'meetings', label: 'Meetings', icon: Calendar },
                    { id: 'team', label: 'Team Status', icon: Users },
                    ...commonItems
                ];

            default:
                return commonItems;
        }
    };

    const menuItems = getMenuItems();

    return (
        <>
            {/* Backdrop */}
            <div
                className="fixed inset-0 z-40"
                onClick={onClose}
            />

            {/* Start Menu */}
            <div className="xp-menu" style={{ bottom: '30px', left: '0px' }}>
                {/* User Info */}
                <div className="px-3 py-2 border-b border-gray-300 bg-gray-100">
                    <div className="text-sm font-semibold text-gray-800">{user.name}</div>
                    <div className="text-xs text-gray-600 capitalize">{user.role.replace('_', ' ')}</div>
                </div>

                {/* Menu Items */}
                {menuItems.map((item) => {
                    const Icon = item.icon;
                    return (
                        <div
                            key={item.id}
                            className="xp-menu-item"
                            onClick={() => {
                                onMenuItemClick(item.id);
                                onClose();
                            }}
                        >
                            <Icon size={14} />
                            {item.label}
                        </div>
                    );
                })}
            </div>
        </>
    );
}
