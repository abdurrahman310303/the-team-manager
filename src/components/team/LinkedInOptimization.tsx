'use client';

import React, { useState } from 'react';
import { Plus, Edit, Trash2, Linkedin, User, Eye, Users, Calendar, CheckCircle, AlertCircle, Clock } from 'lucide-react';
import { LinkedInProfile } from '@/types/team';
import Button from '@/components/windows-xp/Button';

interface LinkedInOptimizationProps {
    onClose: () => void;
}

export default function LinkedInOptimization({ onClose }: LinkedInOptimizationProps) {
    const [profiles, setProfiles] = useState<LinkedInProfile[]>([
        {
            id: '1',
            memberId: '1',
            profileUrl: 'https://linkedin.com/in/abdur-rahman',
            lastOptimized: new Date('2024-01-15'),
            optimizationNotes: 'Updated headline, added skills, optimized summary',
            connections: 850,
            views: 120,
            status: 'optimized'
        },
        {
            id: '2',
            memberId: '2',
            profileUrl: 'https://linkedin.com/in/wasif-ullah',
            lastOptimized: new Date('2024-01-10'),
            optimizationNotes: 'Added recent projects, updated experience section',
            connections: 650,
            views: 95,
            status: 'optimized'
        },
        {
            id: '3',
            memberId: '3',
            profileUrl: 'https://linkedin.com/in/muhammad-mubashir',
            lastOptimized: new Date('2024-01-20'),
            optimizationNotes: 'Profile needs more keywords and better summary',
            connections: 420,
            views: 60,
            status: 'needs_optimization'
        },
        {
            id: '4',
            memberId: '4',
            profileUrl: 'https://linkedin.com/in/business-developer',
            lastOptimized: new Date('2024-01-25'),
            optimizationNotes: 'Currently updating profile picture and banner',
            connections: 1200,
            views: 200,
            status: 'in_progress'
        }
    ]);

    const [selectedProfile, setSelectedProfile] = useState<LinkedInProfile | null>(null);
    const [isAdding, setIsAdding] = useState(false);
    const [statusFilter, setStatusFilter] = useState<'all' | 'optimized' | 'needs_optimization' | 'in_progress'>('all');

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'optimized': return 'text-green-600';
            case 'needs_optimization': return 'text-red-600';
            case 'in_progress': return 'text-yellow-600';
            default: return 'text-gray-600';
        }
    };

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'optimized': return <CheckCircle size={16} />;
            case 'needs_optimization': return <AlertCircle size={16} />;
            case 'in_progress': return <Clock size={16} />;
            default: return <Clock size={16} />;
        }
    };

    const getStatusLabel = (status: string) => {
        switch (status) {
            case 'optimized': return 'Optimized';
            case 'needs_optimization': return 'Needs Optimization';
            case 'in_progress': return 'In Progress';
            default: return status;
        }
    };

    const getMemberName = (memberId: string) => {
        const memberNames: { [key: string]: string } = {
            '1': 'Abdur Rahman',
            '2': 'Wasif Ullah',
            '3': 'Muhammad Mubashir',
            '4': 'Business Developer'
        };
        return memberNames[memberId] || `Member ${memberId}`;
    };

    const filteredProfiles = profiles.filter(profile =>
        statusFilter === 'all' || profile.status === statusFilter
    );

    const totalConnections = profiles.reduce((sum, profile) => sum + profile.connections, 0);
    const totalViews = profiles.reduce((sum, profile) => sum + profile.views, 0);
    const optimizedCount = profiles.filter(profile => profile.status === 'optimized').length;
    const optimizationRate = profiles.length > 0 ? (optimizedCount / profiles.length * 100).toFixed(1) : '0';

    const handleDeleteProfile = (id: string) => {
        if (confirm('Are you sure you want to delete this LinkedIn profile?')) {
            setProfiles(profiles.filter(p => p.id !== id));
            if (selectedProfile?.id === id) {
                setSelectedProfile(null);
            }
        }
    };

    const handleAddProfile = () => {
        const newProfile: LinkedInProfile = {
            id: Date.now().toString(),
            memberId: '1',
            profileUrl: 'https://linkedin.com/in/new-profile',
            lastOptimized: new Date(),
            optimizationNotes: 'New profile - needs optimization',
            connections: 0,
            views: 0,
            status: 'needs_optimization'
        };
        setProfiles([newProfile, ...profiles]);
        setSelectedProfile(newProfile);
        setIsAdding(true);
    };

    const handleStatusChange = (id: string, newStatus: string) => {
        setProfiles(profiles.map(profile =>
            profile.id === id ? { ...profile, status: newStatus as any } : profile
        ));
        if (selectedProfile?.id === id) {
            setSelectedProfile({ ...selectedProfile, status: newStatus as any });
        }
    };

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold">LinkedIn Profile Optimization</h2>
                <Button onClick={handleAddProfile}>
                    <Plus size={14} className="mr-1" />
                    Add Profile
                </Button>
            </div>

            {/* Summary Cards */}
            <div className="grid grid-cols-4 gap-4 mb-4">
                <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <Linkedin size={16} className="text-blue-600" />
                        <span className="text-sm font-semibold text-blue-600">Total Profiles</span>
                    </div>
                    <p className="text-lg font-bold text-blue-700">{profiles.length}</p>
                </div>
                <div className="bg-green-50 border border-green-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <CheckCircle size={16} className="text-green-600" />
                        <span className="text-sm font-semibold text-green-600">Optimized</span>
                    </div>
                    <p className="text-lg font-bold text-green-700">{optimizationRate}%</p>
                </div>
                <div className="bg-purple-50 border border-purple-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <Users size={16} className="text-purple-600" />
                        <span className="text-sm font-semibold text-purple-600">Total Connections</span>
                    </div>
                    <p className="text-lg font-bold text-purple-700">{totalConnections.toLocaleString()}</p>
                </div>
                <div className="bg-orange-50 border border-orange-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <Eye size={16} className="text-orange-600" />
                        <span className="text-sm font-semibold text-orange-600">Total Views</span>
                    </div>
                    <p className="text-lg font-bold text-orange-700">{totalViews.toLocaleString()}</p>
                </div>
            </div>

            <div className="flex flex-1 gap-4">
                {/* Profiles List */}
                <div className="w-1/2">
                    {/* Filter Buttons */}
                    <div className="flex gap-2 mb-4">
                        <Button
                            className={statusFilter === 'all' ? 'bg-blue-500 text-white' : ''}
                            onClick={() => setStatusFilter('all')}
                        >
                            All
                        </Button>
                        <Button
                            className={statusFilter === 'optimized' ? 'bg-green-500 text-white' : ''}
                            onClick={() => setStatusFilter('optimized')}
                        >
                            Optimized
                        </Button>
                        <Button
                            className={statusFilter === 'needs_optimization' ? 'bg-red-500 text-white' : ''}
                            onClick={() => setStatusFilter('needs_optimization')}
                        >
                            Needs Work
                        </Button>
                        <Button
                            className={statusFilter === 'in_progress' ? 'bg-yellow-500 text-white' : ''}
                            onClick={() => setStatusFilter('in_progress')}
                        >
                            In Progress
                        </Button>
                    </div>

                    <div className="space-y-2 max-h-96 overflow-y-auto">
                        {filteredProfiles.map((profile) => (
                            <div
                                key={profile.id}
                                className={`p-3 border cursor-pointer ${selectedProfile?.id === profile.id
                                        ? 'bg-blue-100 border-blue-300'
                                        : 'bg-white border-gray-300 hover:bg-gray-50'
                                    }`}
                                onClick={() => setSelectedProfile(profile)}
                            >
                                <div className="flex justify-between items-start">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2 mb-1">
                                            {getStatusIcon(profile.status)}
                                            <span className="font-semibold">{getMemberName(profile.memberId)}</span>
                                        </div>
                                        <p className="text-sm text-gray-600 mb-1 truncate">{profile.profileUrl}</p>
                                        <div className="flex items-center gap-4 text-xs text-gray-500">
                                            <span className="flex items-center gap-1">
                                                <Users size={12} />
                                                {profile.connections} connections
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <Eye size={12} />
                                                {profile.views} views
                                            </span>
                                            <span className={`${getStatusColor(profile.status)}`}>
                                                {getStatusLabel(profile.status)}
                                            </span>
                                        </div>
                                    </div>
                                    <div className="flex gap-1">
                                        <button
                                            className="p-1 hover:bg-gray-200 rounded"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                setIsAdding(true);
                                            }}
                                        >
                                            <Edit size={12} />
                                        </button>
                                        <button
                                            className="p-1 hover:bg-red-200 rounded text-red-600"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                handleDeleteProfile(profile.id);
                                            }}
                                        >
                                            <Trash2 size={12} />
                                        </button>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Profile Details */}
                <div className="w-1/2 pl-4 border-l">
                    {selectedProfile ? (
                        <div>
                            <h3 className="text-lg font-bold mb-4">Profile Details</h3>
                            <div className="space-y-3">
                                <div className="flex items-center gap-2">
                                    <User size={16} />
                                    <span className="font-semibold">Member:</span>
                                    <span>{getMemberName(selectedProfile.memberId)}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Linkedin size={16} />
                                    <span className="font-semibold">Profile URL:</span>
                                    <a
                                        href={selectedProfile.profileUrl}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="text-blue-600 hover:underline text-sm"
                                    >
                                        View Profile
                                    </a>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Users size={16} />
                                    <span className="font-semibold">Connections:</span>
                                    <span className="font-bold text-blue-600">{selectedProfile.connections.toLocaleString()}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Eye size={16} />
                                    <span className="font-semibold">Profile Views:</span>
                                    <span className="font-bold text-green-600">{selectedProfile.views.toLocaleString()}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Calendar size={16} />
                                    <span className="font-semibold">Last Optimized:</span>
                                    <span>{selectedProfile.lastOptimized.toLocaleDateString()}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    {getStatusIcon(selectedProfile.status)}
                                    <span className="font-semibold">Status:</span>
                                    <span className={`${getStatusColor(selectedProfile.status)}`}>
                                        {getStatusLabel(selectedProfile.status)}
                                    </span>
                                </div>

                                <div>
                                    <span className="font-semibold">Optimization Notes:</span>
                                    <p className="text-sm text-gray-600 mt-1">{selectedProfile.optimizationNotes}</p>
                                </div>

                                {/* Status Change Buttons */}
                                <div className="mt-4">
                                    <span className="font-semibold mb-2 block">Change Status:</span>
                                    <div className="flex gap-2 flex-wrap">
                                        <Button
                                            className={selectedProfile.status === 'optimized' ? 'bg-green-500 text-white' : ''}
                                            onClick={() => handleStatusChange(selectedProfile.id, 'optimized')}
                                        >
                                            Optimized
                                        </Button>
                                        <Button
                                            className={selectedProfile.status === 'needs_optimization' ? 'bg-red-500 text-white' : ''}
                                            onClick={() => handleStatusChange(selectedProfile.id, 'needs_optimization')}
                                        >
                                            Needs Work
                                        </Button>
                                        <Button
                                            className={selectedProfile.status === 'in_progress' ? 'bg-yellow-500 text-white' : ''}
                                            onClick={() => handleStatusChange(selectedProfile.id, 'in_progress')}
                                        >
                                            In Progress
                                        </Button>
                                    </div>
                                </div>

                                {/* Quick Actions */}
                                <div className="mt-4">
                                    <span className="font-semibold mb-2 block">Quick Actions:</span>
                                    <div className="flex gap-2 flex-wrap">
                                        <Button>
                                            Update Notes
                                        </Button>
                                        <Button>
                                            View Analytics
                                        </Button>
                                        <Button>
                                            Schedule Review
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="flex items-center justify-center h-full text-gray-500">
                            Select a LinkedIn profile to view details
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
