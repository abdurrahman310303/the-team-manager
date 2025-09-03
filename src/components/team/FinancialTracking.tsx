'use client';

import React, { useState } from 'react';
import { Plus, Edit, Trash2, DollarSign, TrendingUp, TrendingDown, Calendar, User, Briefcase } from 'lucide-react';
import { FinancialRecord } from '@/types/team';
import Button from '@/components/windows-xp/Button';

interface FinancialTrackingProps {
    onClose: () => void;
}

export default function FinancialTracking({ onClose }: FinancialTrackingProps) {
    const [records, setRecords] = useState<FinancialRecord[]>([
        {
            id: '1',
            type: 'expense',
            category: 'salary',
            amount: 5000,
            description: 'Abdur Rahman - January Salary',
            date: new Date('2024-01-31'),
            relatedMember: '1'
        },
        {
            id: '2',
            type: 'expense',
            category: 'salary',
            amount: 4500,
            description: 'Wasif Ullah - January Salary',
            date: new Date('2024-01-31'),
            relatedMember: '2'
        },
        {
            id: '3',
            type: 'expense',
            category: 'salary',
            amount: 4000,
            description: 'Muhammad Mubashir - January Salary',
            date: new Date('2024-01-31'),
            relatedMember: '3'
        },
        {
            id: '4',
            type: 'expense',
            category: 'salary',
            amount: 3000,
            description: 'Business Developer - January Salary',
            date: new Date('2024-01-31'),
            relatedMember: '4'
        },
        {
            id: '5',
            type: 'expense',
            category: 'upwork_connects',
            amount: 200,
            description: 'Upwork Connects Purchase - 200 connects',
            date: new Date('2024-01-15'),
        },
        {
            id: '6',
            type: 'income',
            category: 'investment',
            amount: 20000,
            description: 'Saad Salman - Monthly Investment',
            date: new Date('2024-01-01'),
            relatedMember: '5'
        },
        {
            id: '7',
            type: 'income',
            category: 'project_income',
            amount: 15000,
            description: 'E-commerce Website Project',
            date: new Date('2024-01-20'),
            relatedProject: '1'
        }
    ]);

    const [selectedRecord, setSelectedRecord] = useState<FinancialRecord | null>(null);
    const [isAdding, setIsAdding] = useState(false);
    const [filter, setFilter] = useState<'all' | 'income' | 'expense'>('all');

    const getTypeColor = (type: string) => {
        return type === 'income' ? 'text-green-600' : 'text-red-600';
    };

    const getTypeIcon = (type: string) => {
        return type === 'income' ? <TrendingUp size={16} /> : <TrendingDown size={16} />;
    };

    const getCategoryLabel = (category: string) => {
        switch (category) {
            case 'salary': return 'Salary';
            case 'upwork_connects': return 'Upwork Connects';
            case 'project_income': return 'Project Income';
            case 'investment': return 'Investment';
            case 'other': return 'Other';
            default: return category;
        }
    };

    const filteredRecords = records.filter(record =>
        filter === 'all' || record.type === filter
    );

    const totalIncome = records
        .filter(r => r.type === 'income')
        .reduce((sum, r) => sum + r.amount, 0);

    const totalExpenses = records
        .filter(r => r.type === 'expense')
        .reduce((sum, r) => sum + r.amount, 0);

    const netProfit = totalIncome - totalExpenses;

    const handleDeleteRecord = (id: string) => {
        if (confirm('Are you sure you want to delete this financial record?')) {
            setRecords(records.filter(r => r.id !== id));
            if (selectedRecord?.id === id) {
                setSelectedRecord(null);
            }
        }
    };

    const handleAddRecord = () => {
        const newRecord: FinancialRecord = {
            id: Date.now().toString(),
            type: 'expense',
            category: 'other',
            amount: 0,
            description: 'New Record',
            date: new Date(),
        };
        setRecords([newRecord, ...records]);
        setSelectedRecord(newRecord);
        setIsAdding(true);
    };

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold">Financial Tracking</h2>
                <Button onClick={handleAddRecord}>
                    <Plus size={14} className="mr-1" />
                    Add Record
                </Button>
            </div>

            {/* Summary Cards */}
            <div className="grid grid-cols-3 gap-4 mb-4">
                <div className="bg-green-50 border border-green-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <TrendingUp size={16} className="text-green-600" />
                        <span className="text-sm font-semibold text-green-600">Total Income</span>
                    </div>
                    <p className="text-lg font-bold text-green-700">${totalIncome.toLocaleString()}</p>
                </div>
                <div className="bg-red-50 border border-red-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <TrendingDown size={16} className="text-red-600" />
                        <span className="text-sm font-semibold text-red-600">Total Expenses</span>
                    </div>
                    <p className="text-lg font-bold text-red-700">${totalExpenses.toLocaleString()}</p>
                </div>
                <div className={`border p-3 rounded ${netProfit >= 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}`}>
                    <div className="flex items-center gap-2">
                        <DollarSign size={16} className={netProfit >= 0 ? 'text-green-600' : 'text-red-600'} />
                        <span className={`text-sm font-semibold ${netProfit >= 0 ? 'text-green-600' : 'text-red-600'}`}>Net Profit</span>
                    </div>
                    <p className={`text-lg font-bold ${netProfit >= 0 ? 'text-green-700' : 'text-red-700'}`}>
                        ${netProfit.toLocaleString()}
                    </p>
                </div>
            </div>

            <div className="flex flex-1 gap-4">
                {/* Records List */}
                <div className="w-1/2">
                    {/* Filter Buttons */}
                    <div className="flex gap-2 mb-4">
                        <Button
                            className={filter === 'all' ? 'bg-blue-500 text-white' : ''}
                            onClick={() => setFilter('all')}
                        >
                            All
                        </Button>
                        <Button
                            className={filter === 'income' ? 'bg-green-500 text-white' : ''}
                            onClick={() => setFilter('income')}
                        >
                            Income
                        </Button>
                        <Button
                            className={filter === 'expense' ? 'bg-red-500 text-white' : ''}
                            onClick={() => setFilter('expense')}
                        >
                            Expenses
                        </Button>
                    </div>

                    <div className="space-y-2 max-h-96 overflow-y-auto">
                        {filteredRecords.map((record) => (
                            <div
                                key={record.id}
                                className={`p-3 border cursor-pointer ${selectedRecord?.id === record.id
                                        ? 'bg-blue-100 border-blue-300'
                                        : 'bg-white border-gray-300 hover:bg-gray-50'
                                    }`}
                                onClick={() => setSelectedRecord(record)}
                            >
                                <div className="flex justify-between items-start">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2">
                                            {getTypeIcon(record.type)}
                                            <span className={`font-semibold ${getTypeColor(record.type)}`}>
                                                ${record.amount.toLocaleString()}
                                            </span>
                                        </div>
                                        <p className="text-sm text-gray-700">{record.description}</p>
                                        <div className="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                            <span>{getCategoryLabel(record.category)}</span>
                                            <span>{record.date.toLocaleDateString()}</span>
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
                                                handleDeleteRecord(record.id);
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

                {/* Record Details */}
                <div className="w-1/2 pl-4 border-l">
                    {selectedRecord ? (
                        <div>
                            <h3 className="text-lg font-bold mb-4">Record Details</h3>
                            <div className="space-y-3">
                                <div className="flex items-center gap-2">
                                    {getTypeIcon(selectedRecord.type)}
                                    <span className="font-semibold">Type:</span>
                                    <span className={`capitalize ${getTypeColor(selectedRecord.type)}`}>
                                        {selectedRecord.type}
                                    </span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <DollarSign size={16} />
                                    <span className="font-semibold">Amount:</span>
                                    <span className={`font-bold ${getTypeColor(selectedRecord.type)}`}>
                                        ${selectedRecord.amount.toLocaleString()}
                                    </span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Briefcase size={16} />
                                    <span className="font-semibold">Category:</span>
                                    <span>{getCategoryLabel(selectedRecord.category)}</span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Calendar size={16} />
                                    <span className="font-semibold">Date:</span>
                                    <span>{selectedRecord.date.toLocaleDateString()}</span>
                                </div>

                                <div>
                                    <span className="font-semibold">Description:</span>
                                    <p className="text-sm text-gray-600 mt-1">{selectedRecord.description}</p>
                                </div>

                                {selectedRecord.relatedMember && (
                                    <div className="flex items-center gap-2">
                                        <User size={16} />
                                        <span className="font-semibold">Related Member:</span>
                                        <span>Member ID: {selectedRecord.relatedMember}</span>
                                    </div>
                                )}

                                {selectedRecord.relatedProject && (
                                    <div className="flex items-center gap-2">
                                        <Briefcase size={16} />
                                        <span className="font-semibold">Related Project:</span>
                                        <span>Project ID: {selectedRecord.relatedProject}</span>
                                    </div>
                                )}
                            </div>
                        </div>
                    ) : (
                        <div className="flex items-center justify-center h-full text-gray-500">
                            Select a financial record to view details
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
