'use client';

import React, { useState } from 'react';
import { HelpCircle, Book, MessageCircle, Mail, Phone, ExternalLink, User, Briefcase, DollarSign } from 'lucide-react';
import Button from '@/components/windows-xp/Button';

interface HelpProps {
    onClose: () => void;
}

export default function Help({ onClose }: HelpProps) {
    const [activeSection, setActiveSection] = useState<'overview' | 'team' | 'finance' | 'upwork' | 'linkedin' | 'contact'>('overview');

    const sections = [
        { id: 'overview', label: 'Overview', icon: Book },
        { id: 'team', label: 'Team Management', icon: Book },
        { id: 'finance', label: 'Financial Tracking', icon: Book },
        { id: 'upwork', label: 'Upwork Bidding', icon: Book },
        { id: 'linkedin', label: 'LinkedIn Optimization', icon: Book },
        { id: 'contact', label: 'Contact Support', icon: MessageCircle }
    ];

    const SectionButton = ({ section, isActive, onClick }: {
        section: typeof sections[0];
        isActive: boolean;
        onClick: () => void;
    }) => {
        const Icon = section.icon;
        return (
            <button
                className={`flex items-center gap-2 px-4 py-2 text-sm w-full text-left ${isActive ? 'bg-blue-500 text-white' : 'bg-gray-100 hover:bg-gray-200'
                    }`}
                onClick={onClick}
            >
                <Icon size={16} />
                {section.label}
            </button>
        );
    };

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold">Help & Support</h2>
                <div className="flex items-center gap-2 text-sm text-gray-600">
                    <HelpCircle size={16} />
                    Team Manager v1.0
                </div>
            </div>

            <div className="flex flex-1 gap-4">
                {/* Sidebar */}
                <div className="w-1/4">
                    <div className="space-y-1">
                        {sections.map((section) => (
                            <SectionButton
                                key={section.id}
                                section={section}
                                isActive={activeSection === section.id}
                                onClick={() => setActiveSection(section.id as any)}
                            />
                        ))}
                    </div>
                </div>

                {/* Content */}
                <div className="flex-1 pl-4 border-l overflow-y-auto">
                    {activeSection === 'overview' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Welcome to Team Manager</h3>
                            <p className="text-gray-600">
                                Team Manager is a comprehensive Windows XP-style business management application
                                designed to help you manage your development team, track finances, handle Upwork
                                bidding, and optimize LinkedIn profiles.
                            </p>

                            <h4 className="font-semibold">Key Features:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li>Team member management with roles and salary tracking</li>
                                <li>Financial tracking for income, expenses, and investments</li>
                                <li>Upwork bidding management with connect tracking</li>
                                <li>LinkedIn profile optimization monitoring</li>
                                <li>Windows XP-style interface for nostalgic experience</li>
                            </ul>

                            <h4 className="font-semibold">Getting Started:</h4>
                            <ol className="list-decimal list-inside space-y-1 text-gray-600">
                                <li>Click on desktop icons or use the Start menu to open applications</li>
                                <li>Drag windows around the desktop like classic Windows XP</li>
                                <li>Use minimize, maximize, and close buttons on windows</li>
                                <li>Access different features through the taskbar</li>
                            </ol>
                        </div>
                    )}

                    {activeSection === 'team' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Team Management</h3>
                            <p className="text-gray-600">
                                Manage your team members, their roles, salaries, and skills.
                            </p>

                            <h4 className="font-semibold">Features:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li>Add, edit, and delete team members</li>
                                <li>Track roles: Developer, Business Developer, Investor</li>
                                <li>Monitor salaries and payment schedules</li>
                                <li>Record skills and notes for each member</li>
                                <li>View detailed member information</li>
                            </ul>

                            <h4 className="font-semibold">How to Use:</h4>
                            <ol className="list-decimal list-inside space-y-1 text-gray-600">
                                <li>Click "Add Member" to create a new team member</li>
                                <li>Select a member from the list to view details</li>
                                <li>Use edit and delete buttons to modify members</li>
                                <li>View salary information and join dates</li>
                            </ol>
                        </div>
                    )}

                    {activeSection === 'finance' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Financial Tracking</h3>
                            <p className="text-gray-600">
                                Track all financial activities including income, expenses, and investments.
                            </p>

                            <h4 className="font-semibold">Features:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li>Record income from projects and investments</li>
                                <li>Track expenses like salaries and Upwork connects</li>
                                <li>View financial summaries and net profit</li>
                                <li>Filter records by type and category</li>
                                <li>Link records to team members and projects</li>
                            </ul>

                            <h4 className="font-semibold">Categories:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li><strong>Salary:</strong> Team member payments</li>
                                <li><strong>Upwork Connects:</strong> Connects purchases</li>
                                <li><strong>Project Income:</strong> Revenue from completed projects</li>
                                <li><strong>Investment:</strong> Funding from investors</li>
                                <li><strong>Other:</strong> Miscellaneous expenses</li>
                            </ul>
                        </div>
                    )}

                    {activeSection === 'upwork' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Upwork Bidding Management</h3>
                            <p className="text-gray-600">
                                Manage your Upwork bids, track connects usage, and monitor success rates.
                            </p>

                            <h4 className="font-semibold">Features:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li>Track all submitted bids and their status</li>
                                <li>Monitor connects usage per bid</li>
                                <li>Update bid status (Submitted, Interview, Hired, Rejected)</li>
                                <li>View success rates and total budget</li>
                                <li>Filter bids by status</li>
                            </ul>

                            <h4 className="font-semibold">Bid Statuses:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li><strong>Submitted:</strong> Bid has been sent to client</li>
                                <li><strong>Interview:</strong> Client is considering your proposal</li>
                                <li><strong>Hired:</strong> Successfully won the project</li>
                                <li><strong>Rejected:</strong> Client declined the proposal</li>
                            </ul>
                        </div>
                    )}

                    {activeSection === 'linkedin' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">LinkedIn Profile Optimization</h3>
                            <p className="text-gray-600">
                                Monitor and optimize LinkedIn profiles for better visibility and networking.
                            </p>

                            <h4 className="font-semibold">Features:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li>Track profile optimization status</li>
                                <li>Monitor connections and profile views</li>
                                <li>Record optimization notes and progress</li>
                                <li>Schedule regular profile reviews</li>
                                <li>Track optimization success rates</li>
                            </ul>

                            <h4 className="font-semibold">Optimization Status:</h4>
                            <ul className="list-disc list-inside space-y-1 text-gray-600">
                                <li><strong>Optimized:</strong> Profile is fully optimized</li>
                                <li><strong>Needs Optimization:</strong> Profile requires updates</li>
                                <li><strong>In Progress:</strong> Currently being optimized</li>
                            </ul>
                        </div>
                    )}

                    {activeSection === 'contact' && (
                        <div className="space-y-4">
                            <h3 className="text-lg font-semibold">Contact Support</h3>
                            <p className="text-gray-600">
                                Need help? Contact our support team for assistance.
                            </p>

                            <div className="space-y-4">
                                <div className="bg-blue-50 border border-blue-200 p-4 rounded">
                                    <h4 className="font-semibold text-blue-800 mb-2">Development Team</h4>
                                    <div className="space-y-2 text-sm">
                                        <div className="flex items-center gap-2">
                                            <User size={16} />
                                            <span>Abdur Rahman - Lead Developer</span>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <User size={16} />
                                            <span>Wasif Ullah - Backend Specialist</span>
                                        </div>
                                        <div className="flex items-center gap-2">
                                            <User size={16} />
                                            <span>Muhammad Mubashir - Full-stack Developer</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-green-50 border border-green-200 p-4 rounded">
                                    <h4 className="font-semibold text-green-800 mb-2">Business Development</h4>
                                    <div className="space-y-2 text-sm">
                                        <div className="flex items-center gap-2">
                                            <Briefcase size={16} />
                                            <span>Business Developer - Upwork & LinkedIn</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-purple-50 border border-purple-200 p-4 rounded">
                                    <h4 className="font-semibold text-purple-800 mb-2">Investor</h4>
                                    <div className="space-y-2 text-sm">
                                        <div className="flex items-center gap-2">
                                            <DollarSign size={16} />
                                            <span>Saad Salman - Financial Backer</span>
                                        </div>
                                    </div>
                                </div>

                                <div className="bg-gray-50 border border-gray-200 p-4 rounded">
                                    <h4 className="font-semibold text-gray-800 mb-2">Support Options</h4>
                                    <div className="space-y-2">
                                        <Button className="w-full justify-start">
                                            <Mail size={14} className="mr-2" />
                                            Email Support
                                        </Button>
                                        <Button className="w-full justify-start">
                                            <MessageCircle size={14} className="mr-2" />
                                            Live Chat
                                        </Button>
                                        <Button className="w-full justify-start">
                                            <Phone size={14} className="mr-2" />
                                            Phone Support
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
