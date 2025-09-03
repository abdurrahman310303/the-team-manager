'use client';

import React, { useState } from 'react';
import { Plus, Edit, Trash2, Briefcase, DollarSign, Calendar, User, Target, CheckCircle, XCircle, Clock } from 'lucide-react';
import { UpworkBid } from '@/types/team';
import Button from '@/components/windows-xp/Button';

interface UpworkBiddingProps {
    onClose: () => void;
}

export default function UpworkBidding({ onClose }: UpworkBiddingProps) {
    const [bids, setBids] = useState<UpworkBid[]>([
        {
            id: '1',
            title: 'E-commerce Website Development',
            description: 'Need a full-stack developer to build an e-commerce website with React and Node.js',
            client: 'TechStart Inc.',
            budget: 5000,
            connectsUsed: 6,
            bidDate: new Date('2024-01-15'),
            status: 'hired',
            assignedBD: '4'
        },
        {
            id: '2',
            title: 'Mobile App Development',
            description: 'Looking for a React Native developer to create a mobile app for iOS and Android',
            client: 'MobileCorp',
            budget: 8000,
            connectsUsed: 6,
            bidDate: new Date('2024-01-20'),
            status: 'interview',
            assignedBD: '4'
        },
        {
            id: '3',
            title: 'Database Optimization',
            description: 'Need help optimizing PostgreSQL database for better performance',
            client: 'DataFlow Systems',
            budget: 2000,
            connectsUsed: 4,
            bidDate: new Date('2024-01-25'),
            status: 'submitted',
            assignedBD: '4'
        },
        {
            id: '4',
            title: 'UI/UX Design for SaaS Platform',
            description: 'Looking for a designer to create modern UI/UX for our SaaS application',
            client: 'SaaS Solutions',
            budget: 3000,
            connectsUsed: 4,
            bidDate: new Date('2024-01-28'),
            status: 'rejected',
            assignedBD: '4'
        }
    ]);

    const [selectedBid, setSelectedBid] = useState<UpworkBid | null>(null);
    const [isAdding, setIsAdding] = useState(false);
    const [statusFilter, setStatusFilter] = useState<'all' | 'submitted' | 'interview' | 'hired' | 'rejected'>('all');

    const getStatusColor = (status: string) => {
        switch (status) {
            case 'submitted': return 'text-blue-600';
            case 'interview': return 'text-yellow-600';
            case 'hired': return 'text-green-600';
            case 'rejected': return 'text-red-600';
            default: return 'text-gray-600';
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

    const getStatusLabel = (status: string) => {
        switch (status) {
            case 'submitted': return 'Submitted';
            case 'interview': return 'Interview';
            case 'hired': return 'Hired';
            case 'rejected': return 'Rejected';
            default: return status;
        }
    };

    const filteredBids = bids.filter(bid =>
        statusFilter === 'all' || bid.status === statusFilter
    );

    const totalConnectsUsed = bids.reduce((sum, bid) => sum + bid.connectsUsed, 0);
    const totalBudget = bids.filter(bid => bid.status === 'hired').reduce((sum, bid) => sum + bid.budget, 0);
    const successRate = bids.length > 0 ? (bids.filter(bid => bid.status === 'hired').length / bids.length * 100).toFixed(1) : '0';

    const handleDeleteBid = (id: string) => {
        if (confirm('Are you sure you want to delete this bid?')) {
            setBids(bids.filter(b => b.id !== id));
            if (selectedBid?.id === id) {
                setSelectedBid(null);
            }
        }
    };

    const handleAddBid = () => {
        const newBid: UpworkBid = {
            id: Date.now().toString(),
            title: 'New Bid',
            description: 'New bid description',
            client: 'New Client',
            budget: 0,
            connectsUsed: 6,
            bidDate: new Date(),
            status: 'submitted',
            assignedBD: '4'
        };
        setBids([newBid, ...bids]);
        setSelectedBid(newBid);
        setIsAdding(true);
    };

    const handleStatusChange = (id: string, newStatus: string) => {
        setBids(bids.map(bid =>
            bid.id === id ? { ...bid, status: newStatus as any } : bid
        ));
        if (selectedBid?.id === id) {
            setSelectedBid({ ...selectedBid, status: newStatus as any });
        }
    };

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold">Upwork Bidding Management</h2>
                <Button onClick={handleAddBid}>
                    <Plus size={14} className="mr-1" />
                    Add Bid
                </Button>
            </div>

            {/* Summary Cards */}
            <div className="grid grid-cols-4 gap-4 mb-4">
                <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <Target size={16} className="text-blue-600" />
                        <span className="text-sm font-semibold text-blue-600">Total Bids</span>
                    </div>
                    <p className="text-lg font-bold text-blue-700">{bids.length}</p>
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
                        <Briefcase size={16} className="text-orange-600" />
                        <span className="text-sm font-semibold text-orange-600">Connects Used</span>
                    </div>
                    <p className="text-lg font-bold text-orange-700">{totalConnectsUsed}</p>
                </div>
            </div>

            <div className="flex flex-1 gap-4">
                {/* Bids List */}
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
                            className={statusFilter === 'submitted' ? 'bg-blue-500 text-white' : ''}
                            onClick={() => setStatusFilter('submitted')}
                        >
                            Submitted
                        </Button>
                        <Button
                            className={statusFilter === 'interview' ? 'bg-yellow-500 text-white' : ''}
                            onClick={() => setStatusFilter('interview')}
                        >
                            Interview
                        </Button>
                        <Button
                            className={statusFilter === 'hired' ? 'bg-green-500 text-white' : ''}
                            onClick={() => setStatusFilter('hired')}
                        >
                            Hired
                        </Button>
                        <Button
                            className={statusFilter === 'rejected' ? 'bg-red-500 text-white' : ''}
                            onClick={() => setStatusFilter('rejected')}
                        >
                            Rejected
                        </Button>
                    </div>

                    <div className="space-y-2 max-h-96 overflow-y-auto">
                        {filteredBids.map((bid) => (
                            <div
                                key={bid.id}
                                className={`p-3 border cursor-pointer ${selectedBid?.id === bid.id
                                        ? 'bg-blue-100 border-blue-300'
                                        : 'bg-white border-gray-300 hover:bg-gray-50'
                                    }`}
                                onClick={() => setSelectedBid(bid)}
                            >
                                <div className="flex justify-between items-start">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2 mb-1">
                                            {getStatusIcon(bid.status)}
                                            <span className="font-semibold">{bid.title}</span>
                                        </div>
                                        <p className="text-sm text-gray-600 mb-1">{bid.client}</p>
                                        <div className="flex items-center gap-4 text-xs text-gray-500">
                                            <span className="flex items-center gap-1">
                                                <DollarSign size={12} />
                                                ${bid.budget.toLocaleString()}
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <Target size={12} />
                                                {bid.connectsUsed} connects
                                            </span>
                                            <span className={`${getStatusColor(bid.status)}`}>
                                                {getStatusLabel(bid.status)}
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
                                                handleDeleteBid(bid.id);
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

                {/* Bid Details */}
                <div className="w-1/2 pl-4 border-l">
                    {selectedBid ? (
                        <div>
                            <h3 className="text-lg font-bold mb-4">Bid Details</h3>
                            <div className="space-y-3">
                                <div className="flex items-center gap-2">
                                    <Briefcase size={16} />
                                    <span className="font-semibold">Title:</span>
                                    <span>{selectedBid.title}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <User size={16} />
                                    <span className="font-semibold">Client:</span>
                                    <span>{selectedBid.client}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <DollarSign size={16} />
                                    <span className="font-semibold">Budget:</span>
                                    <span className="font-bold text-green-600">${selectedBid.budget.toLocaleString()}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Target size={16} />
                                    <span className="font-semibold">Connects Used:</span>
                                    <span>{selectedBid.connectsUsed}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Calendar size={16} />
                                    <span className="font-semibold">Bid Date:</span>
                                    <span>{selectedBid.bidDate.toLocaleDateString()}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    {getStatusIcon(selectedBid.status)}
                                    <span className="font-semibold">Status:</span>
                                    <span className={`${getStatusColor(selectedBid.status)}`}>
                                        {getStatusLabel(selectedBid.status)}
                                    </span>
                                </div>

                                <div>
                                    <span className="font-semibold">Description:</span>
                                    <p className="text-sm text-gray-600 mt-1">{selectedBid.description}</p>
                                </div>

                                <div className="flex items-center gap-2">
                                    <User size={16} />
                                    <span className="font-semibold">Assigned BD:</span>
                                    <span>Business Developer (ID: {selectedBid.assignedBD})</span>
                                </div>

                                {/* Status Change Buttons */}
                                <div className="mt-4">
                                    <span className="font-semibold mb-2 block">Change Status:</span>
                                    <div className="flex gap-2 flex-wrap">
                                        <Button
                                            className={selectedBid.status === 'submitted' ? 'bg-blue-500 text-white' : ''}
                                            onClick={() => handleStatusChange(selectedBid.id, 'submitted')}
                                        >
                                            Submitted
                                        </Button>
                                        <Button
                                            className={selectedBid.status === 'interview' ? 'bg-yellow-500 text-white' : ''}
                                            onClick={() => handleStatusChange(selectedBid.id, 'interview')}
                                        >
                                            Interview
                                        </Button>
                                        <Button
                                            className={selectedBid.status === 'hired' ? 'bg-green-500 text-white' : ''}
                                            onClick={() => handleStatusChange(selectedBid.id, 'hired')}
                                        >
                                            Hired
                                        </Button>
                                        <Button
                                            className={selectedBid.status === 'rejected' ? 'bg-red-500 text-white' : ''}
                                            onClick={() => handleStatusChange(selectedBid.id, 'rejected')}
                                        >
                                            Rejected
                                        </Button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="flex items-center justify-center h-full text-gray-500">
                            Select a bid to view details
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
