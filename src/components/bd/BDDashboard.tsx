'use client';

import React, { useState } from 'react';
import { Briefcase, Target, Users, TrendingUp, CheckCircle, Clock, XCircle, Plus } from 'lucide-react';
import Button from '@/components/windows-xp/Button';

interface Proposal {
    id: string;
    title: string;
    client: string;
    budget: number;
    connectsUsed: number;
    status: 'submitted' | 'interview' | 'hired' | 'rejected';
    date: Date;
    description: string;
    notes?: string;
}

interface Lead {
    id: string;
    name: string;
    company: string;
    source: string;
    status: 'new' | 'contacted' | 'qualified' | 'proposal_sent' | 'closed_won' | 'closed_lost';
    value: number;
    date: Date;
    notes?: string;
}

interface BDDashboardProps {
    onClose: () => void;
}

export default function BDDashboard({ onClose }: BDDashboardProps) {
    const [proposals, setProposals] = useState<Proposal[]>([
        {
            id: '1',
            title: 'E-commerce Website Development',
            client: 'TechStart Inc.',
            budget: 5000,
            connectsUsed: 6,
            status: 'hired',
            date: new Date('2024-01-15'),
            description: 'Full-stack e-commerce website with React and Node.js',
            notes: 'Client was very responsive, quick decision'
        },
        {
            id: '2',
            title: 'Mobile App Development',
            client: 'MobileCorp',
            budget: 8000,
            connectsUsed: 6,
            status: 'interview',
            date: new Date('2024-01-20'),
            description: 'React Native mobile app for iOS and Android',
            notes: 'Scheduled interview for next week'
        },
        {
            id: '3',
            title: 'Database Optimization',
            client: 'DataFlow Systems',
            budget: 2000,
            connectsUsed: 4,
            status: 'submitted',
            date: new Date('2024-01-25'),
            description: 'PostgreSQL database optimization project',
            notes: 'Waiting for client response'
        }
    ]);

    const [leads, setLeads] = useState<Lead[]>([
        {
            id: '1',
            name: 'John Smith',
            company: 'TechStart Inc.',
            source: 'Upwork',
            status: 'closed_won',
            value: 5000,
            date: new Date('2024-01-10'),
            notes: 'Converted to client, very satisfied'
        },
        {
            id: '2',
            name: 'Sarah Johnson',
            company: 'MobileCorp',
            source: 'LinkedIn',
            status: 'proposal_sent',
            value: 8000,
            date: new Date('2024-01-18'),
            notes: 'Sent proposal, waiting for response'
        },
        {
            id: '3',
            name: 'Mike Davis',
            company: 'DataFlow Systems',
            source: 'Upwork',
            status: 'qualified',
            value: 2000,
            date: new Date('2024-01-22'),
            notes: 'Qualified lead, preparing proposal'
        }
    ]);

    const [selectedProposal, setSelectedProposal] = useState<Proposal | null>(null);
    const [selectedLead, setSelectedLead] = useState<Lead | null>(null);
    const [activeTab, setActiveTab] = useState<'proposals' | 'leads'>('proposals');

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'submitted': return 'text-blue-600 bg-blue-100';
            case 'interview': return 'text-yellow-600 bg-yellow-100';
            case 'hired': return 'text-green-600 bg-green-100';
            case 'rejected': return 'text-red-600 bg-red-100';
            case 'new': return 'text-gray-600 bg-gray-100';
            case 'contacted': return 'text-blue-600 bg-blue-100';
            case 'qualified': return 'text-purple-600 bg-purple-100';
            case 'proposal_sent': return 'text-orange-600 bg-orange-100';
            case 'closed_won': return 'text-green-600 bg-green-100';
            case 'closed_lost': return 'text-red-600 bg-red-100';
            default: return 'text-gray-600 bg-gray-100';
        }
    };

    const getStatusIcon = (status: string) => {
        switch (status) {
            case 'submitted': return <Clock size={16} />;
            case 'interview': return <Target size={16} />;
            case 'hired': return <CheckCircle size={16} />;
            case 'rejected': return <XCircle size={16} />;
            default: return <Clock size={16} />;
        }
    };

    const totalProposals = proposals.length;
    const hiredProposals = proposals.filter(p => p.status === 'hired').length;
    const successRate = totalProposals > 0 ? (hiredProposals / totalProposals * 100).toFixed(1) : '0';
    const totalConnectsUsed = proposals.reduce((sum, p) => sum + p.connectsUsed, 0);
    const totalBudget = proposals.filter(p => p.status === 'hired').reduce((sum, p) => sum + p.budget, 0);

    const totalLeads = leads.length;
    const wonLeads = leads.filter(l => l.status === 'closed_won').length;
    const leadConversionRate = totalLeads > 0 ? (wonLeads / totalLeads * 100).toFixed(1) : '0';
    const totalLeadValue = leads.reduce((sum, l) => sum + l.value, 0);

    const handleStatusChange = (id: string, newStatus: string, type: 'proposal' | 'lead') => {
        if (type === 'proposal') {
            setProposals(proposals.map(p =>
                p.id === id ? { ...p, status: newStatus as any } : p
            ));
            if (selectedProposal?.id === id) {
                setSelectedProposal({ ...selectedProposal, status: newStatus as any });
            }
        } else {
            setLeads(leads.map(l =>
                l.id === id ? { ...l, status: newStatus as any } : l
            ));
            if (selectedLead?.id === id) {
                setSelectedLead({ ...selectedLead, status: newStatus as any });
            }
        }
    };

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold flex items-center gap-2">
                    <Briefcase size={20} />
                    Business Development Dashboard
                </h2>
                <div className="flex gap-2">
                    <Button onClick={() => setActiveTab('proposals')}>
                        <Target size={14} className="mr-1" />
                        Proposals
                    </Button>
                    <Button onClick={() => setActiveTab('leads')}>
                        <Users size={14} className="mr-1" />
                        Leads
                    </Button>
                </div>
            </div>

            {/* Stats */}
            {activeTab === 'proposals' ? (
                <div className="grid grid-cols-4 gap-4 mb-4">
                    <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <Target size={16} className="text-blue-600" />
                            <span className="text-sm font-semibold text-blue-600">Total Proposals</span>
                        </div>
                        <p className="text-lg font-bold text-blue-700">{totalProposals}</p>
                    </div>
                    <div className="bg-green-50 border border-green-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <CheckCircle size={16} className="text-green-600" />
                            <span className="text-sm font-semibold text-green-600">Success Rate</span>
                        </div>
                        <p className="text-lg font-bold text-green-700">{successRate}%</p>
                    </div>
                    <div className="bg-purple-50 border border-purple-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <DollarSign size={16} className="text-purple-600" />
                            <span className="text-sm font-semibold text-purple-600">Total Budget</span>
                        </div>
                        <p className="text-lg font-bold text-purple-700">${totalBudget.toLocaleString()}</p>
                    </div>
                    <div className="bg-orange-50 border border-orange-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <TrendingUp size={16} className="text-orange-600" />
                            <span className="text-sm font-semibold text-orange-600">Connects Used</span>
                        </div>
                        <p className="text-lg font-bold text-orange-700">{totalConnectsUsed}</p>
                    </div>
                </div>
            ) : (
                <div className="grid grid-cols-4 gap-4 mb-4">
                    <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <Users size={16} className="text-blue-600" />
                            <span className="text-sm font-semibold text-blue-600">Total Leads</span>
                        </div>
                        <p className="text-lg font-bold text-blue-700">{totalLeads}</p>
                    </div>
                    <div className="bg-green-50 border border-green-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <CheckCircle size={16} className="text-green-600" />
                            <span className="text-sm font-semibold text-green-600">Conversion Rate</span>
                        </div>
                        <p className="text-lg font-bold text-green-700">{leadConversionRate}%</p>
                    </div>
                    <div className="bg-purple-50 border border-purple-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <DollarSign size={16} className="text-purple-600" />
                            <span className="text-sm font-semibold text-purple-600">Total Value</span>
                        </div>
                        <p className="text-lg font-bold text-purple-700">${totalLeadValue.toLocaleString()}</p>
                    </div>
                    <div className="bg-orange-50 border border-orange-200 p-3 rounded">
                        <div className="flex items-center gap-2">
                            <TrendingUp size={16} className="text-orange-600" />
                            <span className="text-sm font-semibold text-orange-600">Won Leads</span>
                        </div>
                        <p className="text-lg font-bold text-orange-700">{wonLeads}</p>
                    </div>
                </div>
            )}

            <div className="flex flex-1 gap-4">
                {/* List */}
                <div className="w-1/2">
                    <div className="space-y-2 max-h-96 overflow-y-auto">
                        {activeTab === 'proposals' ? (
                            proposals.map((proposal) => (
                                <div
                                    key={proposal.id}
                                    className={`p-3 border cursor-pointer ${selectedProposal?.id === proposal.id
                                            ? 'bg-blue-100 border-blue-300'
                                            : 'bg-white border-gray-300 hover:bg-gray-50'
                                        }`}
                                    onClick={() => setSelectedProposal(proposal)}
                                >
                                    <div className="flex justify-between items-start">
                                        <div className="flex-1">
                                            <div className="flex items-center gap-2 mb-1">
                                                {getStatusIcon(proposal.status)}
                                                <span className="font-semibold">{proposal.title}</span>
                                            </div>
                                            <p className="text-sm text-gray-600">{proposal.client}</p>
                                            <div className="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                                <span>${proposal.budget.toLocaleString()}</span>
                                                <span>{proposal.connectsUsed} connects</span>
                                                <span className={`${getStatusColor(proposal.status)} px-2 py-1 rounded`}>
                                                    {proposal.status}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            ))
                        ) : (
                            leads.map((lead) => (
                                <div
                                    key={lead.id}
                                    className={`p-3 border cursor-pointer ${selectedLead?.id === lead.id
                                            ? 'bg-blue-100 border-blue-300'
                                            : 'bg-white border-gray-300 hover:bg-gray-50'
                                        }`}
                                    onClick={() => setSelectedLead(lead)}
                                >
                                    <div className="flex justify-between items-start">
                                        <div className="flex-1">
                                            <div className="flex items-center gap-2 mb-1">
                                                <span className="font-semibold">{lead.name}</span>
                                            </div>
                                            <p className="text-sm text-gray-600">{lead.company}</p>
                                            <div className="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                                <span>${lead.value.toLocaleString()}</span>
                                                <span>{lead.source}</span>
                                                <span className={`${getStatusColor(lead.status)} px-2 py-1 rounded`}>
                                                    {lead.status.replace('_', ' ')}
                                                </span>
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
                    {activeTab === 'proposals' ? (
                        selectedProposal ? (
                            <div>
                                <h3 className="text-lg font-bold mb-4">Proposal Details</h3>
                                <div className="space-y-3">
                                    <div>
                                        <span className="font-semibold">Title:</span>
                                        <p className="text-gray-600">{selectedProposal.title}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Client:</span>
                                        <p className="text-gray-600">{selectedProposal.client}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Budget:</span>
                                        <p className="text-gray-600">${selectedProposal.budget.toLocaleString()}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Connects Used:</span>
                                        <p className="text-gray-600">{selectedProposal.connectsUsed}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Status:</span>
                                        <span className={`ml-2 px-2 py-1 text-xs rounded ${getStatusColor(selectedProposal.status)}`}>
                                            {selectedProposal.status}
                                        </span>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Description:</span>
                                        <p className="text-gray-600">{selectedProposal.description}</p>
                                    </div>

                                    {selectedProposal.notes && (
                                        <div>
                                            <span className="font-semibold">Notes:</span>
                                            <p className="text-gray-600">{selectedProposal.notes}</p>
                                        </div>
                                    )}

                                    <div className="mt-4">
                                        <span className="font-semibold mb-2 block">Update Status:</span>
                                        <div className="flex gap-2 flex-wrap">
                                            <Button
                                                className={selectedProposal.status === 'submitted' ? 'bg-blue-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedProposal.id, 'submitted', 'proposal')}
                                            >
                                                Submitted
                                            </Button>
                                            <Button
                                                className={selectedProposal.status === 'interview' ? 'bg-yellow-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedProposal.id, 'interview', 'proposal')}
                                            >
                                                Interview
                                            </Button>
                                            <Button
                                                className={selectedProposal.status === 'hired' ? 'bg-green-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedProposal.id, 'hired', 'proposal')}
                                            >
                                                Hired
                                            </Button>
                                            <Button
                                                className={selectedProposal.status === 'rejected' ? 'bg-red-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedProposal.id, 'rejected', 'proposal')}
                                            >
                                                Rejected
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-center justify-center h-full text-gray-500">
                                Select a proposal to view details
                            </div>
                        )
                    ) : (
                        selectedLead ? (
                            <div>
                                <h3 className="text-lg font-bold mb-4">Lead Details</h3>
                                <div className="space-y-3">
                                    <div>
                                        <span className="font-semibold">Name:</span>
                                        <p className="text-gray-600">{selectedLead.name}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Company:</span>
                                        <p className="text-gray-600">{selectedLead.company}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Source:</span>
                                        <p className="text-gray-600">{selectedLead.source}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Value:</span>
                                        <p className="text-gray-600">${selectedLead.value.toLocaleString()}</p>
                                    </div>

                                    <div>
                                        <span className="font-semibold">Status:</span>
                                        <span className={`ml-2 px-2 py-1 text-xs rounded ${getStatusColor(selectedLead.status)}`}>
                                            {selectedLead.status.replace('_', ' ')}
                                        </span>
                                    </div>

                                    {selectedLead.notes && (
                                        <div>
                                            <span className="font-semibold">Notes:</span>
                                            <p className="text-gray-600">{selectedLead.notes}</p>
                                        </div>
                                    )}

                                    <div className="mt-4">
                                        <span className="font-semibold mb-2 block">Update Status:</span>
                                        <div className="flex gap-2 flex-wrap">
                                            <Button
                                                className={selectedLead.status === 'new' ? 'bg-gray-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedLead.id, 'new', 'lead')}
                                            >
                                                New
                                            </Button>
                                            <Button
                                                className={selectedLead.status === 'contacted' ? 'bg-blue-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedLead.id, 'contacted', 'lead')}
                                            >
                                                Contacted
                                            </Button>
                                            <Button
                                                className={selectedLead.status === 'qualified' ? 'bg-purple-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedLead.id, 'qualified', 'lead')}
                                            >
                                                Qualified
                                            </Button>
                                            <Button
                                                className={selectedLead.status === 'closed_won' ? 'bg-green-500 text-white' : ''}
                                                onClick={() => handleStatusChange(selectedLead.id, 'closed_won', 'lead')}
                                            >
                                                Won
                                            </Button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ) : (
                            <div className="flex items-center justify-center h-full text-gray-500">
                                Select a lead to view details
                            </div>
                        )
                    )}
                </div>
            </div>
        </div>
    );
}
