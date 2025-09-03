'use client';

import React, { useState } from 'react';
import { Settings as SettingsIcon, User, DollarSign, Briefcase, Linkedin, Save, RefreshCw } from 'lucide-react';
import Button from '@/components/windows-xp/Button';

interface SettingsProps {
    onClose: () => void;
}

export default function Settings({ onClose }: SettingsProps) {
    const [settings, setSettings] = useState({
        companyName: 'The Team Manager',
        currency: 'USD',
        timezone: 'UTC',
        defaultConnectsPerBid: 6,
        salaryPaymentDay: 1,
        upworkConnectsPrice: 1.5,
        linkedinOptimizationInterval: 30,
        notifications: {
            salaryReminders: true,
            bidUpdates: true,
            profileOptimization: true,
            financialAlerts: true
        }
    });

    const [activeTab, setActiveTab] = useState<'general' | 'financial' | 'upwork' | 'linkedin' | 'notifications'>('general');

    const handleSave = () => {
        // In a real app, this would save to a backend
        alert('Settings saved successfully!');
    };

    const handleReset = () => {
        if (confirm('Are you sure you want to reset all settings to default?')) {
            setSettings({
                companyName: 'The Team Manager',
                currency: 'USD',
                timezone: 'UTC',
                defaultConnectsPerBid: 6,
                salaryPaymentDay: 1,
                upworkConnectsPrice: 1.5,
                linkedinOptimizationInterval: 30,
                notifications: {
                    salaryReminders: true,
                    bidUpdates: true,
                    profileOptimization: true,
                    financialAlerts: true
                }
            });
        }
    };

    const TabButton = ({ id, label, icon: Icon, isActive, onClick }: {
        id: string;
        label: string;
        icon: any;
        isActive: boolean;
        onClick: () => void;
    }) => (
        <button
            className={`flex items-center gap-2 px-4 py-2 text-sm ${isActive ? 'bg-blue-500 text-white' : 'bg-gray-100 hover:bg-gray-200'
                }`}
            onClick={onClick}
        >
            <Icon size={16} />
            {label}
        </button>
    );

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold">Settings</h2>
                <div className="flex gap-2">
                    <Button onClick={handleReset}>
                        <RefreshCw size={14} className="mr-1" />
                        Reset
                    </Button>
                    <Button onClick={handleSave}>
                        <Save size={14} className="mr-1" />
                        Save
                    </Button>
                </div>
            </div>

            <div className="flex flex-1 gap-4">
                {/* Sidebar */}
                <div className="w-1/4">
                    <div className="space-y-1">
                        <TabButton
                            id="general"
                            label="General"
                            icon={SettingsIcon}
                            isActive={activeTab === 'general'}
                            onClick={() => setActiveTab('general')}
                        />
                        <TabButton
                            id="financial"
                            label="Financial"
                            icon={DollarSign}
                            isActive={activeTab === 'financial'}
                            onClick={() => setActiveTab('financial')}
                        />
                        <TabButton
                            id="upwork"
                            label="Upwork"
                            icon={Briefcase}
                            isActive={activeTab === 'upwork'}
                            onClick={() => setActiveTab('upwork')}
                        />
                        <TabButton
                            id="linkedin"
                            label="LinkedIn"
                            icon={Linkedin}
                            isActive={activeTab === 'linkedin'}
                            onClick={() => setActiveTab('linkedin')}
                        />
                        <TabButton
                            id="notifications"
                            label="Notifications"
                            icon={User}
                            isActive={activeTab === 'notifications'}
                            onClick={() => setActiveTab('notifications')}
                        />
                    </div>
                </div>

                {/* Content */}
                <div className="flex-1 pl-4 border-l">
                    {activeTab === 'general' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">General Settings</h3>

                            <div>
                                <label className="block text-sm font-medium mb-1">Company Name</label>
                                <input
                                    type="text"
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.companyName}
                                    onChange={(e) => setSettings({ ...settings, companyName: e.target.value })}
                                />
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">Currency</label>
                                <select
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.currency}
                                    onChange={(e) => setSettings({ ...settings, currency: e.target.value })}
                                >
                                    <option value="USD">USD ($)</option>
                                    <option value="EUR">EUR (€)</option>
                                    <option value="GBP">GBP (£)</option>
                                    <option value="PKR">PKR (₨)</option>
                                </select>
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">Timezone</label>
                                <select
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.timezone}
                                    onChange={(e) => setSettings({ ...settings, timezone: e.target.value })}
                                >
                                    <option value="UTC">UTC</option>
                                    <option value="EST">Eastern Time</option>
                                    <option value="PST">Pacific Time</option>
                                    <option value="PKT">Pakistan Time</option>
                                </select>
                            </div>
                        </div>
                    )}

                    {activeTab === 'financial' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Financial Settings</h3>

                            <div>
                                <label className="block text-sm font-medium mb-1">Salary Payment Day</label>
                                <select
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.salaryPaymentDay}
                                    onChange={(e) => setSettings({ ...settings, salaryPaymentDay: parseInt(e.target.value) })}
                                >
                                    {Array.from({ length: 31 }, (_, i) => (
                                        <option key={i + 1} value={i + 1}>{i + 1}</option>
                                    ))}
                                </select>
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">Upwork Connects Price (per connect)</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.upworkConnectsPrice}
                                    onChange={(e) => setSettings({ ...settings, upworkConnectsPrice: parseFloat(e.target.value) })}
                                />
                            </div>
                        </div>
                    )}

                    {activeTab === 'upwork' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Upwork Settings</h3>

                            <div>
                                <label className="block text-sm font-medium mb-1">Default Connects per Bid</label>
                                <input
                                    type="number"
                                    min="1"
                                    max="10"
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.defaultConnectsPerBid}
                                    onChange={(e) => setSettings({ ...settings, defaultConnectsPerBid: parseInt(e.target.value) })}
                                />
                            </div>

                            <div>
                                <label className="block text-sm font-medium mb-1">Connects Price per Unit</label>
                                <input
                                    type="number"
                                    step="0.01"
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.upworkConnectsPrice}
                                    onChange={(e) => setSettings({ ...settings, upworkConnectsPrice: parseFloat(e.target.value) })}
                                />
                            </div>
                        </div>
                    )}

                    {activeTab === 'linkedin' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">LinkedIn Settings</h3>

                            <div>
                                <label className="block text-sm font-medium mb-1">Optimization Review Interval (days)</label>
                                <input
                                    type="number"
                                    min="7"
                                    max="90"
                                    className="w-full p-2 border border-gray-300 rounded"
                                    value={settings.linkedinOptimizationInterval}
                                    onChange={(e) => setSettings({ ...settings, linkedinOptimizationInterval: parseInt(e.target.value) })}
                                />
                            </div>
                        </div>
                    )}

                    {activeTab === 'notifications' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Notification Settings</h3>

                            <div className="space-y-3">
                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={settings.notifications.salaryReminders}
                                        onChange={(e) => setSettings({
                                            ...settings,
                                            notifications: {
                                                ...settings.notifications,
                                                salaryReminders: e.target.checked
                                            }
                                        })}
                                    />
                                    <span>Salary Payment Reminders</span>
                                </label>

                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={settings.notifications.bidUpdates}
                                        onChange={(e) => setSettings({
                                            ...settings,
                                            notifications: {
                                                ...settings.notifications,
                                                bidUpdates: e.target.checked
                                            }
                                        })}
                                    />
                                    <span>Upwork Bid Updates</span>
                                </label>

                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={settings.notifications.profileOptimization}
                                        onChange={(e) => setSettings({
                                            ...settings,
                                            notifications: {
                                                ...settings.notifications,
                                                profileOptimization: e.target.checked
                                            }
                                        })}
                                    />
                                    <span>LinkedIn Profile Optimization</span>
                                </label>

                                <label className="flex items-center gap-2">
                                    <input
                                        type="checkbox"
                                        checked={settings.notifications.financialAlerts}
                                        onChange={(e) => setSettings({
                                            ...settings,
                                            notifications: {
                                                ...settings.notifications,
                                                financialAlerts: e.target.checked
                                            }
                                        })}
                                    />
                                    <span>Financial Alerts</span>
                                </label>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
