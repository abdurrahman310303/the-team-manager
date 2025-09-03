'use client';

import React, { useState } from 'react';
import { Code, Calendar, Users, Clock, CheckCircle, AlertCircle, Plus, FileText } from 'lucide-react';
import Button from '@/components/windows-xp/Button';

interface Project {
    id: string;
    name: string;
    client: string;
    status: 'planning' | 'in_progress' | 'review' | 'completed' | 'on_hold';
    startDate: Date;
    endDate?: Date;
    progress: number;
    description: string;
    technologies: string[];
    notes?: string;
}

interface Meeting {
    id: string;
    title: string;
    client: string;
    date: Date;
    duration: number; // in minutes
    type: 'client_meeting' | 'team_meeting' | 'standup' | 'review';
    attendees: string[];
    notes?: string;
    actionItems?: string[];
}

interface DeveloperDashboardProps {
    onClose: () => void;
}

export default function DeveloperDashboard({ onClose }: DeveloperDashboardProps) {
    const [projects, setProjects] = useState<Project[]>([
        {
            id: '1',
            name: 'E-commerce Website',
            client: 'TechStart Inc.',
            status: 'completed',
            startDate: new Date('2024-01-15'),
            endDate: new Date('2024-02-15'),
            progress: 100,
            description: 'Full-stack e-commerce website with React frontend and Node.js backend',
            technologies: ['React', 'Node.js', 'MongoDB', 'Stripe'],
            notes: 'Successfully delivered on time, client very satisfied'
        },
        {
            id: '2',
            name: 'Mobile App Development',
            client: 'MobileCorp',
            status: 'in_progress',
            startDate: new Date('2024-01-20'),
            progress: 65,
            description: 'React Native mobile app for iOS and Android platforms',
            technologies: ['React Native', 'Firebase', 'Redux'],
            notes: 'Currently working on user authentication module'
        },
        {
            id: '3',
            name: 'Database Optimization',
            client: 'DataFlow Systems',
            status: 'planning',
            startDate: new Date('2024-02-01'),
            progress: 10,
            description: 'PostgreSQL database optimization and performance tuning',
            technologies: ['PostgreSQL', 'Python', 'SQL'],
            notes: 'Waiting for client requirements finalization'
        }
    ]);

    const [meetings, setMeetings] = useState<Meeting[]>([
        {
            id: '1',
            title: 'Project Kickoff - E-commerce',
            client: 'TechStart Inc.',
            date: new Date('2024-01-15'),
            duration: 60,
            type: 'client_meeting',
            attendees: ['Abdur Rahman', 'TechStart Team'],
            notes: 'Discussed project requirements and timeline',
            actionItems: ['Set up development environment', 'Create project repository']
        },
        {
            id: '2',
            title: 'Weekly Standup',
            client: 'Internal',
            date: new Date('2024-01-22'),
            duration: 30,
            type: 'standup',
            attendees: ['Abdur Rahman', 'Wasif Ullah', 'Muhammad Mubashir'],
            notes: 'Discussed progress and blockers',
            actionItems: ['Complete user authentication', 'Review code quality']
        },
        {
            id: '3',
            title: 'Client Review Meeting',
            client: 'MobileCorp',
            date: new Date('2024-01-25'),
            duration: 45,
            type: 'client_meeting',
            attendees: ['Abdur Rahman', 'MobileCorp Team'],
            notes: 'Presented progress and gathered feedback',
            actionItems: ['Implement suggested changes', 'Update UI design']
        }
    ]);

    const [selectedProject, setSelectedProject] = useState<Project | null>(null);
    const [selectedMeeting, setSelectedMeeting] = useState<Meeting | null>(null);
    const [activeTab, setActiveTab] = useState<'projects' | 'meetings'>('projects');

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'planning': return 'text-gray-600 bg-gray-100';
            case 'in_progress': return 'text-blue-600 bg-blue-100';
            case 'review': return 'text-yellow-600 bg-yellow-100';
            case 'completed': return 'text-green-600 bg-green-100';
            case 'on_hold': return 'text-red-600 bg-red-100';
            default: return 'text-gray-600 bg-gray-100';
        }
    };

    const getTypeColor = (type: string) => {
        switch (type) {
            case 'client_meeting': return 'text-blue-600 bg-blue-100';
            case 'team_meeting': return 'text-green-600 bg-green-100';
            case 'standup': return 'text-purple-600 bg-purple-100';
            case 'review': return 'text-orange-600 bg-orange-100';
            default: return 'text-gray-600 bg-gray-100';
        }
    };

    const totalProjects = projects.length;
    const completedProjects = projects.filter(p => p.status === 'completed').length;
    const inProgressProjects = projects.filter(p => p.status === 'in_progress').length;
    const averageProgress = projects.length > 0 ?
        (projects.reduce((sum, p) => sum + p.progress, 0) / projects.length).toFixed(1) : '0';

    const totalMeetings = meetings.length;
    const totalMeetingTime = meetings.reduce((sum, m) => sum + m.duration, 0);
    const clientMeetings = meetings.filter(m => m.type === 'client_meeting').length;

    const handleProjectStatusChange = (id: string, newStatus: string) => {
        setProjects(projects.map(p =>
            p.id === id ? { ...p, status: newStatus as any } : p
        ));
        if (selectedProject?.id === id) {
            setSelectedProject({ ...selectedProject, status: newStatus as any });
        }
    };

    const handleProgressUpdate = (id: string, newProgress: number) => {
        setProjects(projects.map(p =>
            p.id === id ? { ...p, progress: newProgress } : p
        ));
        if (selectedProject?.id === id) {
            setSelectedProject({ ...selectedProject, progress: newProgress });
        }
    };

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold flex items-center gap-2">
                    <Code size={20} />
                    Developer Dashboard
                </h2>
                <div className="flex gap-2">
                    <Button onClick={() => setActiveTab('projects')}>
                        <Code size={14} className="mr-1" />
                        Projects
                    </Button>
                    <Button onClick={() => setActiveTab('meetings')}>
                        <Calendar size={14} className="mr-1" />
                        Meetings
                    </Button>
                </div>
            </div>

            {/* Stats */}
            {activeTab === 'projects' ? (
                <div className="grid grid-cols-4 gap-4 mb-4">
                    <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <Code size={16} className="text-blue-600" />
                            <span className="text-sm font-semibold text-blue-600">Total Projects</span>
                        </div>
                        <p className="text-lg font-bold text-blue-700">{totalProjects}</p>
                    </div>
                    <div className="bg-green-50 border border-green-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <CheckCircle size={16} className="text-green-600" />
                            <span className="text-sm font-semibold text-green-600">Completed</span>
                        </div>
                        <p className="text-lg font-bold text-green-700">{completedProjects}</p>
                    </div>
                    <div className="bg-yellow-50 border border-yellow-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <Clock size={16} className="text-yellow-600" />
                            <span className="text-sm font-semibold text-yellow-600">In Progress</span>
                        </div>
                        <p className="text-lg font-bold text-yellow-700">{inProgressProjects}</p>
                    </div>
                    <div className="bg-purple-50 border border-purple-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <AlertCircle size={16} className="text-purple-600" />
                            <span className="text-sm font-semibold text-purple-600">Avg Progress</span>
                        </div>
                        <p className="text-lg font-bold text-purple-700">{averageProgress}%</p>
                    </div>
                </div>
            ) : (
                <div className="grid grid-cols-4 gap-4 mb-4">
                    <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <Calendar size={16} className="text-blue-600" />
                            <span className="text-sm font-semibold text-blue-600">Total Meetings</span>
                        </div>
                        <p className="text-lg font-bold text-blue-700">{totalMeetings}</p>
                    </div>
                    <div className="bg-green-50 border border-green-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <Users size={16} className="text-green-600" />
                            <span className="text-sm font-semibold text-green-600">Client Meetings</span>
                        </div>
                        <p className="text-lg font-bold text-green-700">{clientMeetings}</p>
                    </div>
                    <div className="bg-purple-50 border border-purple-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <Clock size={16} className="text-purple-600" />
                            <span className="text-sm font-semibold text-purple-600">Total Time</span>
                        </div>
                        <p className="text-lg font-bold text-purple-700">{totalMeetingTime}m</p>
                    </div>
                    <div className="bg-orange-50 border border-orange-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <FileText size={16} className="text-orange-600" />
                            <span className="text-sm font-semibold text-orange-600">Avg Duration</span>
                        </div>
                        <p className="text-lg font-bold text-orange-700">
                            {totalMeetings > 0 ? (totalMeetingTime / totalMeetings).toFixed(0) : '0'}m
                        </p>
                    </div>
                </div>
            )}

            <div className="flex flex-1 gap-4">
                {/* List */}
                <div className="w-1/2">
                    <div className="space-y-2 max-h-96 overflow-y-auto">
                        {activeTab === 'projects' ? (
                            projects.map((project) => (
                                <div
                                    key={project.id}
                                    className={`p-3 border cursor-pointer ${selectedProject?.id === project.id
                                            ? 'bg-blue-100 border-blue-300'
                                            : 'bg-white border-gray-300 hover:bg-gray-50'
                                        }`}
                                    onClick={() => setSelectedProject(project)}
                                >
                                    <div className="flex justify-between items-start">
                                        <div className="flex-1">
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="font-semibold">{project.name}</span>
                                                <span className={`px-2 py-1 text-xs rounded ${getStatusColor(project.status)}`}>
                                                    {project.status.replace('_', ' ')}
                                                </span>
                                            </div>
                                            <p className="text-sm text-gray-600">{project.client}</p>
                                            <div className="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                                <span>{project.progress}% complete</span>
                                                <span>{project.technologies.join(', ')}</span>
                                            </div>
                                            <div className="w-full bg-gray-200 rounded-full h-2 mt-2">
                                                <div
                                                    className="bg-blue-600 h-2 rounded-full"
                                                    style={{ width: `${project.progress}%` }}
                                                ></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            meetings.map((meeting) => (
                                <div
                                    key={meeting.id}
                                    className={`p-3 border cursor-pointer ${selectedMeeting?.id === meeting.id
                                            ? 'bg-blue-100 border-blue-300'
                                            : 'bg-white border-gray-300 hover:bg-gray-50'
                                        }`}
                                    onClick={() => setSelectedMeeting(meeting)}
                                >
                                    <div className="flex justify-between items-start">
                                        <div className="flex-1">
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="font-semibold">{meeting.title}</span>
                                                <span className={`px-2 py-1 text-xs rounded ${getTypeColor(meeting.type)}`}>
                                                    {meeting.type.replace('_', ' ')}
                                                </span>
                                            </div>
                                            <p className="text-sm text-gray-600">{meeting.client}</p>
                                            <div className="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                                <span>{meeting.date.toLocaleDateString()}</span>
                                                <span>{meeting.duration} minutes</span>
                                                <span>{meeting.attendees.length} attendees</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        )}
                    </div>
                </div>

                {/* Details */}
                <div className="w-1/2 pl-4 border-l">
                    {activeTab === 'projects' ? (
                        selectedProject ? (
                            <div>
                                <h3 className="text-lg font-bold mb-4">Project Details</h3>
                                <div className="space-y-3">
                                    <div>
                                        <span className="font-semibold">Name:</span>
                                        <p className="text-gray-600">{selectedProject.name}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Client:</span>
                                        <p className="text-gray-600">{selectedProject.client}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Status:</span>
                                        <span className={`ml-2 px-2 py-1 text-xs rounded ${getStatusColor(selectedProject.status)}`}>
                                            {selectedProject.status.replace('_', ' ')}
                                        </span>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Progress:</span>
                                        <div className="flex items-center gap-2 mt-1">
                                            <div className="flex-1 bg-gray-200 rounded-full h-2">
                                                <div
                                                    className="bg-blue-600 h-2 rounded-full"
                                                    style={{ width: `${selectedProject.progress}%` }}
                                                ></div>
                                            </div>
                                            <span className="text-sm">{selectedProject.progress}%</span>
                                        </div>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Technologies:</span>
                                        <div className="flex flex-wrap gap-1 mt-1">
                                            {selectedProject.technologies.map((tech, index) => (
                                                <span
                                                    key={index}
                                                    className="px-2 py-1 bg-blue-100 text-blue-800 text-xs rounded"
                                                >
                                                    {tech}
                                                </span>
                                            ))}
                                        </div>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Description:</span>
                                        <p className="text-gray-600">{selectedProject.description}</p>
                                    </div>

                                    {selectedProject.notes && (
                                        <div>
                                            <span className="font-semibold">Notes:</span>
                                            <p className="text-gray-600">{selectedProject.notes}</p>
                                        </div>
                                    )}

                                    <div className="mt-4">
                                        <span className="font-semibold mb-2 block">Update Status:</span>
                                        <div className="flex gap-2 flex-wrap">
                                            <Button
                                                className={selectedProject.status === 'planning' ? 'bg-gray-500 text-white' : ''}
                                                onClick={() => handleProjectStatusChange(selectedProject.id, 'planning')}
                                            >
                                                Planning
                                            </Button>
                                            <Button
                                                className={selectedProject.status === 'in_progress' ? 'bg-blue-500 text-white' : ''}
                                                onClick={() => handleProjectStatusChange(selectedProject.id, 'in_progress')}
                                            >
                                                In Progress
                                            </Button>
                                            <Button
                                                className={selectedProject.status === 'review' ? 'bg-yellow-500 text-white' : ''}
                                                onClick={() => handleProjectStatusChange(selectedProject.id, 'review')}
                                            >
                                                Review
                                            </Button>
                                            <Button
                                                className={selectedProject.status === 'completed' ? 'bg-green-500 text-white' : ''}
                                                onClick={() => handleProjectStatusChange(selectedProject.id, 'completed')}
                                            >
                                                Completed
                                            </Button>
                                        </div>
                                    </div>

                                    <div className="mt-4">
                                        <span className="font-semibold mb-2 block">Update Progress:</span>
                                        <div className="flex gap-2 items-center">
                                            <input
                                                type="range"
                                                min="0"
                                                max="100"
                                                value={selectedProject.progress}
                                                onChange={(e) => handleProgressUpdate(selectedProject.id, parseInt(e.target.value))}
                                                className="flex-1"
                                            />
                                            <span className="text-sm">{selectedProject.progress}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-center justify-center h-full text-gray-500">
                                Select a project to view details
                            </div>
                        )
                    ) : (
                        selectedMeeting ? (
                            <div>
                                <h3 className="text-lg font-bold mb-4">Meeting Details</h3>
                                <div className="space-y-3">
                                    <div>
                                        <span className="font-semibold">Title:</span>
                                        <p className="text-gray-600">{selectedMeeting.title}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Client:</span>
                                        <p className="text-gray-600">{selectedMeeting.client}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Date:</span>
                                        <p className="text-gray-600">{selectedMeeting.date.toLocaleDateString()}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Duration:</span>
                                        <p className="text-gray-600">{selectedMeeting.duration} minutes</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Type:</span>
                                        <span className={`ml-2 px-2 py-1 text-xs rounded ${getTypeColor(selectedMeeting.type)}`}>
                                            {selectedMeeting.type.replace('_', ' ')}
                                        </span>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Attendees:</span>
                                        <ul className="list-disc list-inside text-gray-600 mt-1">
                                            {selectedMeeting.attendees.map((attendee, index) => (
                                                <li key={index}>{attendee}</li>
                                            ))}
                                        </ul>
                                    </div>

                                    {selectedMeeting.notes && (
                                        <div>
                                            <span className="font-semibold">Notes:</span>
                                            <p className="text-gray-600">{selectedMeeting.notes}</p>
                                        </div>
                                    )}

                                    {selectedMeeting.actionItems && selectedMeeting.actionItems.length > 0 && (
                                        <div>
                                            <span className="font-semibold">Action Items:</span>
                                            <ul className="list-disc list-inside text-gray-600 mt-1">
                                                {selectedMeeting.actionItems.map((item, index) => (
                                                    <li key={index}>{item}</li>
                                                ))}
                                            </ul>
                                        </div>
                                    )}
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-center justify-center h-full text-gray-500">
                                Select a meeting to view details
                            </div>
                        )
                    )}
                </div>
            </div>
        </div>
    );
}
