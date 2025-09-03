'use client';

import React, { useState } from 'react';
import { Plus, Edit, Trash2, User, Mail, Phone, DollarSign, Calendar } from 'lucide-react';
import { TeamMember } from '@/types/team';
import Button from '@/components/windows-xp/Button';

interface TeamMembersProps {
    onClose: () => void;
}

export default function TeamMembers({ onClose }: TeamMembersProps) {
    const [members, setMembers] = useState<TeamMember[]>([
        {
            id: '1',
            name: 'Abdur Rahman',
            role: 'developer',
            email: 'abdur@team.com',
            phone: '+1234567890',
            salary: 5000,
            joinDate: new Date('2024-01-01'),
            status: 'active',
            skills: ['React', 'Node.js', 'TypeScript'],
            notes: 'Lead developer'
        },
        {
            id: '2',
            name: 'Wasif Ullah',
            role: 'developer',
            email: 'wasif@team.com',
            phone: '+1234567891',
            salary: 4500,
            joinDate: new Date('2024-01-15'),
            status: 'active',
            skills: ['Vue.js', 'Python', 'Django'],
            notes: 'Backend specialist'
        },
        {
            id: '3',
            name: 'Muhammad Mubashir',
            role: 'developer',
            email: 'mubashir@team.com',
            phone: '+1234567892',
            salary: 4000,
            joinDate: new Date('2024-02-01'),
            status: 'active',
            skills: ['Angular', 'Java', 'Spring'],
            notes: 'Full-stack developer'
        },
        {
            id: '4',
            name: 'Business Developer',
            role: 'business_developer',
            email: 'bd@team.com',
            phone: '+1234567893',
            salary: 3000,
            joinDate: new Date('2024-02-15'),
            status: 'active',
            skills: ['Sales', 'Marketing', 'LinkedIn'],
            notes: 'Handles Upwork bidding and LinkedIn optimization'
        },
        {
            id: '5',
            name: 'Saad Salman',
            role: 'investor',
            email: 'saad@team.com',
            phone: '+1234567894',
            salary: 0,
            joinDate: new Date('2024-01-01'),
            status: 'active',
            skills: ['Investment', 'Finance'],
            notes: 'Provides funding for connects and salaries'
        }
    ]);

    const [selectedMember, setSelectedMember] = useState<TeamMember | null>(null);
    const [isEditing, setIsEditing] = useState(false);

    const getRoleColor = (role: string) => {
        switch (role) {
            case 'developer': return 'text-blue-600';
            case 'business_developer': return 'text-green-600';
            case 'investor': return 'text-purple-600';
            default: return 'text-gray-600';
        }
    };

    const getRoleLabel = (role: string) => {
        switch (role) {
            case 'developer': return 'Developer';
            case 'business_developer': return 'Business Developer';
            case 'investor': return 'Investor';
            default: return role;
        }
    };

    const handleEditMember = (member: TeamMember) => {
        setSelectedMember(member);
        setIsEditing(true);
    };

    const handleDeleteMember = (id: string) => {
        if (confirm('Are you sure you want to delete this team member?')) {
            setMembers(members.filter(m => m.id !== id));
            if (selectedMember?.id === id) {
                setSelectedMember(null);
            }
        }
    };

    return (
        <div className="p-4 h-full flex">
            {/* Members List */}
            <div className="w-1/2 pr-4">
                <div className="flex justify-between items-center mb-4">
                    <h2 className="text-lg font-bold">Team Members</h2>
                    <Button onClick={() => setIsEditing(true)}>
                        <Plus size={14} className="mr-1" />
                        Add Member
                    </Button>
                </div>

                <div className="space-y-2 max-h-96 overflow-y-auto">
                    {members.map((member) => (
                        <div
                            key={member.id}
                            className={`p-3 border cursor-pointer ${selectedMember?.id === member.id
                                    ? 'bg-blue-100 border-blue-300'
                                    : 'bg-white border-gray-300 hover:bg-gray-50'
                                }`}
                            onClick={() => setSelectedMember(member)}
                        >
                            <div className="flex justify-between items-start">
                                <div>
                                    <h3 className="font-semibold">{member.name}</h3>
                                    <p className={`text-sm ${getRoleColor(member.role)}`}>
                                        {getRoleLabel(member.role)}
                                    </p>
                                    <p className="text-xs text-gray-500">{member.email}</p>
                                </div>
                                <div className="flex gap-1">
                                    <button
                                        className="p-1 hover:bg-gray-200 rounded"
                                        onClick={(e) => {
                                            e.stopPropagation();
                                            handleEditMember(member);
                                        }}
                                    >
                                        <Edit size={12} />
                                    </button>
                                    <button
                                        className="p-1 hover:bg-red-200 rounded text-red-600"
                                        onClick={(e) => {
                                            e.stopPropagation();
                                            handleDeleteMember(member.id);
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

            {/* Member Details */}
            <div className="w-1/2 pl-4 border-l">
                {selectedMember ? (
                    <div>
                        <h3 className="text-lg font-bold mb-4">Member Details</h3>
                        <div className="space-y-3">
                            <div className="flex items-center gap-2">
                                <User size={16} />
                                <span className="font-semibold">{selectedMember.name}</span>
                            </div>

                            <div className="flex items-center gap-2">
                                <span className="text-sm text-gray-600">Role:</span>
                                <span className={`text-sm ${getRoleColor(selectedMember.role)}`}>
                                    {getRoleLabel(selectedMember.role)}
                                </span>
                            </div>

                            <div className="flex items-center gap-2">
                                <Mail size={16} />
                                <span className="text-sm">{selectedMember.email}</span>
                            </div>

                            {selectedMember.phone && (
                                <div className="flex items-center gap-2">
                                    <Phone size={16} />
                                    <span className="text-sm">{selectedMember.phone}</span>
                                </div>
                            )}

                            <div className="flex items-center gap-2">
                                <DollarSign size={16} />
                                <span className="text-sm">${selectedMember.salary.toLocaleString()}/month</span>
                            </div>

                            <div className="flex items-center gap-2">
                                <Calendar size={16} />
                                <span className="text-sm">
                                    Joined: {selectedMember.joinDate.toLocaleDateString()}
                                </span>
                            </div>

                            {selectedMember.skills && selectedMember.skills.length > 0 && (
                                <div>
                                    <span className="text-sm font-semibold">Skills:</span>
                                    <div className="flex flex-wrap gap-1 mt-1">
                                        {selectedMember.skills.map((skill, index) => (
                                            <span
                                                key={index}
                                                className="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded"
                                            >
                                                {skill}
                                            </span>
                                        ))}
                                    </div>
                                </div>
                            )}

                            {selectedMember.notes && (
                                <div>
                                    <span className="text-sm font-semibold">Notes:</span>
                                    <p className="text-sm text-gray-600 mt-1">{selectedMember.notes}</p>
                                </div>
                            )}
                        </div>
                    </div>
                ) : (
                    <div className="flex items-center justify-center h-full text-gray-500">
                        Select a team member to view details
                    </div>
                )}
            </div>
        </div>
    );
}
