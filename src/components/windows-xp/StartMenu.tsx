'use client';

import React from 'react';
import {
    Users,
    DollarSign,
    Briefcase,
    FileText,
    Settings,
    HelpCircle,
    LogOut
} from 'lucide-react';

interface StartMenuProps {
    isOpen: boolean;
    onClose: () => void;
    onMenuItemClick: (item: string) => void;
}

export default function StartMenu({ isOpen, onClose, onMenuItemClick }: StartMenuProps) {
    if (!isOpen) return null;

    const menuItems = [
        { id: 'team', label: 'Team Members', icon: Users },
        { id: 'finance', label: 'Financial Tracking', icon: DollarSign },
        { id: 'upwork', label: 'Upwork Bidding', icon: Briefcase },
        { id: 'linkedin', label: 'LinkedIn Optimization', icon: FileText },
        { id: 'settings', label: 'Settings', icon: Settings },
        { id: 'help', label: 'Help', icon: HelpCircle },
        { id: 'logout', label: 'Logout', icon: LogOut },
    ];

    return (
        <>
            {/* Backdrop */}
            <div
                className="fixed inset-0 z-40"
                onClick={onClose}
            />

            {/* Start Menu */}
            <div className="xp-menu" style={{ bottom: '30px', left: '0px' }}>
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
