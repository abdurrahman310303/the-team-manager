'use client';

import React, { useState } from 'react';
import { Users, UserPlus, Trash2, Edit, Shield, UserCheck, UserX } from 'lucide-react';
import { User } from '@/types/auth';
import Button from '@/components/windows-xp/Button';

interface AdminDashboardProps {
    onClose: () => void;
}

export default function AdminDashboard({ onClose }: AdminDashboardProps) {
    const [users, setUsers] = useState<User[]>([
        {
            id: '1',
            username: 'admin',
            email: 'admin@team.com',
            role: 'admin',
            name: 'System Administrator',
            isActive: true,
            createdAt: new Date('2024-01-01')
        },
        {
            id: '2',
            username: 'saad',
            email: 'saad@team.com',
            role: 'investor',
            name: 'Saad Salman',
            isActive: true,
            createdAt: new Date('2024-01-01')
        },
        {
            id: '3',
            username: 'bd',
            email: 'bd@team.com',
            role: 'business_developer',
            name: 'Business Developer',
            isActive: true,
            createdAt: new Date('2024-02-15')
        },
        {
            id: '4',
            username: 'abdur',
            email: 'abdur@team.com',
            role: 'developer',
            name: 'Abdur Rahman',
            isActive: true,
            createdAt: new Date('2024-01-01')
        },
        {
            id: '5',
            username: 'wasif',
            email: 'wasif@team.com',
            role: 'developer',
            name: 'Wasif Ullah',
            isActive: true,
            createdAt: new Date('2024-01-15')
        },
        {
            id: '6',
            username: 'mubashir',
            email: 'mubashir@team.com',
            role: 'developer',
            name: 'Muhammad Mubashir',
            isActive: true,
            createdAt: new Date('2024-02-01')
        }
    ]);

    const [selectedUser, setSelectedUser] = useState<User | null>(null);
    const [isAddingUser, setIsAddingUser] = useState(false);

    const getRoleColor = (role: string) => {
        switch (role) {
            case 'admin': return 'text-red-600 bg-red-100';
            case 'investor': return 'text-purple-600 bg-purple-100';
            case 'business_developer': return 'text-green-600 bg-green-100';
            case 'developer': return 'text-blue-600 bg-blue-100';
            default: return 'text-gray-600 bg-gray-100';
        }
    };

    const getRoleLabel = (role: string) => {
        switch (role) {
            case 'admin': return 'Administrator';
            case 'investor': return 'Investor';
            case 'business_developer': return 'Business Developer';
            case 'developer': return 'Developer';
            default: return role;
        }
    };

    const handleDeleteUser = (userId: string) => {
        if (confirm('Are you sure you want to delete this user?')) {
            setUsers(users.filter(u => u.id !== userId));
            if (selectedUser?.id === userId) {
                setSelectedUser(null);
            }
        }
    };

    const handleToggleUserStatus = (userId: string) => {
        setUsers(users.map(user =>
            user.id === userId ? { ...user, isActive: !user.isActive } : user
        ));
        if (selectedUser?.id === userId) {
            setSelectedUser({ ...selectedUser, isActive: !selectedUser.isActive });
        }
    };

    const handleAddUser = () => {
        const newUser: User = {
            id: Date.now().toString(),
            username: 'newuser',
            email: 'newuser@team.com',
            role: 'developer',
            name: 'New User',
            isActive: true,
            createdAt: new Date()
        };
        setUsers([...users, newUser]);
        setSelectedUser(newUser);
        setIsAddingUser(true);
    };

    const activeUsers = users.filter(u => u.isActive).length;
    const totalUsers = users.length;

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold flex items-center gap-2">
                    <Shield size={20} />
                    User Management
                </h2>
                <Button onClick={handleAddUser}>
                    <UserPlus size={14} className="mr-1" />
                    Add User
                </Button>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-3 gap-4 mb-4">
                <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <Users size={16} className="text-blue-600" />
                        <span className="text-sm font-semibold text-blue-600">Total Users</span>
                    </div>
                    <p className="text-lg font-bold text-blue-700">{totalUsers}</p>
                </div>
                <div className="bg-green-50 border border-green-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <UserCheck size={16} className="text-green-600" />
                        <span className="text-sm font-semibold text-green-600">Active Users</span>
                    </div>
                    <p className="text-lg font-bold text-green-700">{activeUsers}</p>
                </div>
                <div className="bg-red-50 border border-red-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <UserX size={16} className="text-red-600" />
                        <span className="text-sm font-semibold text-red-600">Inactive Users</span>
                    </div>
                    <p className="text-lg font-bold text-red-700">{totalUsers - activeUsers}</p>
                </div>
            </div>

            <div className="flex flex-1 gap-4">
                {/* Users List */}
                <div className="w-1/2">
                    <div className="space-y-2 max-h-96 overflow-y-auto">
                        {users.map((user) => (
                            <div
                                key={user.id}
                                className={`p-3 border cursor-pointer ${selectedUser?.id === user.id
                                        ? 'bg-blue-100 border-blue-300'
                                        : 'bg-white border-gray-300 hover:bg-gray-50'
                                    }`}
                                onClick={() => setSelectedUser(user)}
                            >
                                <div className="flex justify-between items-start">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2 mb-1">
                                            <span className="font-semibold">{user.name}</span>
                                            <span className={`px-2 py-1 text-xs rounded ${getRoleColor(user.role)}`}>
                                                {getRoleLabel(user.role)}
                                            </span>
                                        </div>
                                        <p className="text-sm text-gray-600">@{user.username}</p>
                                        <p className="text-xs text-gray-500">{user.email}</p>
                                        <div className="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                            <span>Created: {user.createdAt.toLocaleDateString()}</span>
                                            <span className={user.isActive ? 'text-green-600' : 'text-red-600'}>
                                                {user.isActive ? 'Active' : 'Inactive'}
                                            </span>
                                        </div>
                                    </div>
                                    <div className="flex gap-1">
                                        <button
                                            className="p-1 hover:bg-gray-200 rounded"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                setIsAddingUser(true);
                                            }}
                                        >
                                            <Edit size={12} />
                                        </button>
                                        <button
                                            className="p-1 hover:bg-red-200 rounded text-red-600"
                                            onClick={(e) => {
                                                e.stopPropagation();
                                                handleDeleteUser(user.id);
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

                {/* User Details */}
                <div className="w-1/2 pl-4 border-l">
                    {selectedUser ? (
                        <div>
                            <h3 className="text-lg font-bold mb-4">User Details</h3>
                            <div className="space-y-3">
                                <div>
                                    <span className="font-semibold">Name:</span>
                                    <p className="text-gray-600">{selectedUser.name}</p>
                                </div>

                                <div>
                                    <span className="font-semibold">Username:</span>
                                    <p className="text-gray-600">@{selectedUser.username}</p>
                                </div>

                                <div>
                                    <span className="font-semibold">Email:</span>
                                    <p className="text-gray-600">{selectedUser.email}</p>
                                </div>

                                <div>
                                    <span className="font-semibold">Role:</span>
                                    <span className={`ml-2 px-2 py-1 text-xs rounded ${getRoleColor(selectedUser.role)}`}>
                                        {getRoleLabel(selectedUser.role)}
                                    </span>
                                </div>

                                <div>
                                    <span className="font-semibold">Status:</span>
                                    <span className={`ml-2 ${selectedUser.isActive ? 'text-green-600' : 'text-red-600'}`}>
                                        {selectedUser.isActive ? 'Active' : 'Inactive'}
                                    </span>
                                </div>

                                <div>
                                    <span className="font-semibold">Created:</span>
                                    <p className="text-gray-600">{selectedUser.createdAt.toLocaleDateString()}</p>
                                </div>

                                {selectedUser.lastLogin && (
                                    <div>
                                        <span className="font-semibold">Last Login:</span>
                                        <p className="text-gray-600">{selectedUser.lastLogin.toLocaleString()}</p>
                                    </div>
                                )}

                                {/* Actions */}
                                <div className="mt-4">
                                    <span className="font-semibold mb-2 block">Actions:</span>
                                    <div className="flex gap-2 flex-wrap">
                                        <Button
                                            className={selectedUser.isActive ? 'bg-red-500 text-white' : 'bg-green-500 text-white'}
                                            onClick={() => handleToggleUserStatus(selectedUser.id)}
                                        >
                                            {selectedUser.isActive ? 'Deactivate' : 'Activate'}
                                        </Button>
                                        <Button>
                                            Reset Password
                                        </Button>
                                        <Button>
                                            Edit Profile
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="flex items-center justify-center h-full text-gray-500">
                            Select a user to view details
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
