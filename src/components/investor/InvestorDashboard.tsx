'use client';

import React, { useState } from 'react';
import { DollarSign, Upload, Image, FileText, Calendar, TrendingUp, TrendingDown } from 'lucide-react';
import Button from '@/components/windows-xp/Button';

interface FundRecord {
    id: string;
    amount: number;
    description: string;
    date: Date;
    category: 'investment' | 'expense' | 'return';
    images?: string[];
    notes?: string;
}

interface InvestorDashboardProps {
    onClose: () => void;
}

export default function InvestorDashboard({ onClose }: InvestorDashboardProps) {
    const [funds, setFunds] = useState<FundRecord[]>([
        {
            id: '1',
            amount: 20000,
            description: 'Initial Investment - Team Setup',
            date: new Date('2024-01-01'),
            category: 'investment',
            notes: 'Initial funding for team setup and equipment'
        },
        {
            id: '2',
            amount: 15000,
            description: 'Monthly Investment - February',
            date: new Date('2024-02-01'),
            category: 'investment',
            notes: 'Monthly operational funding'
        },
        {
            id: '3',
            amount: 5000,
            description: 'Upwork Connects Purchase',
            date: new Date('2024-01-15'),
            category: 'expense',
            notes: 'Purchased 200 connects for business development'
        },
        {
            id: '4',
            amount: 8000,
            description: 'Project Return - E-commerce Website',
            date: new Date('2024-01-20'),
            category: 'return',
            notes: 'Return from completed project'
        }
    ]);

    const [selectedFund, setSelectedFund] = useState<FundRecord | null>(null);
    const [isAddingFund, setIsAddingFund] = useState(false);
    const [newFund, setNewFund] = useState<Partial<FundRecord>>({
        amount: 0,
        description: '',
        category: 'investment',
        notes: ''
    });

    const getCategoryColor = (category: string) => {
        switch (category) {
            case 'investment': return 'text-blue-600 bg-blue-100';
            case 'expense': return 'text-red-600 bg-red-100';
            case 'return': return 'text-green-600 bg-green-100';
            default: return 'text-gray-600 bg-gray-100';
        }
    };

    const getCategoryIcon = (category: string) => {
        switch (category) {
            case 'investment': return <TrendingUp size={16} />;
            case 'expense': return <TrendingDown size={16} />;
            case 'return': return <DollarSign size={16} />;
            default: return <DollarSign size={16} />;
        }
    };

    const totalInvestment = funds.filter(f => f.category === 'investment').reduce((sum, f) => sum + f.amount, 0);
    const totalExpenses = funds.filter(f => f.category === 'expense').reduce((sum, f) => sum + f.amount, 0);
    const totalReturns = funds.filter(f => f.category === 'return').reduce((sum, f) => sum + f.amount, 0);
    const netValue = totalInvestment - totalExpenses + totalReturns;

    const handleAddFund = () => {
        if (newFund.amount && newFund.description) {
            const fund: FundRecord = {
                id: Date.now().toString(),
                amount: newFund.amount,
                description: newFund.description,
                date: new Date(),
                category: newFund.category as any,
                notes: newFund.notes
            };
            setFunds([fund, ...funds]);
            setNewFund({ amount: 0, description: '', category: 'investment', notes: '' });
            setIsAddingFund(false);
        }
    };

    const handleImageUpload = (e: React.ChangeEvent<HTMLInputElement>) => {
        const files = e.target.files;
        if (files && files.length > 0) {
            // In a real app, you would upload to a server
            console.log('Uploading images:', files);
            alert('Images uploaded successfully! (Demo mode)');
        }
    };

    return (
        <div className="p-4 h-full flex flex-col">
            {/* Header */}
            <div className="flex justify-between items-center mb-4">
                <h2 className="text-lg font-bold flex items-center gap-2">
                    <DollarSign size={20} />
                    Fund Management
                </h2>
                <Button onClick={() => setIsAddingFund(true)}>
                    <DollarSign size={14} className="mr-1" />
                    Add Fund Record
                </Button>
            </div>

            {/* Stats */}
            <div className="grid grid-cols-4 gap-4 mb-4">
                <div className="bg-blue-50 border border-blue-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <TrendingUp size={16} className="text-blue-600" />
                        <span className="text-sm font-semibold text-blue-600">Total Investment</span>
                    </div>
                    <p className="text-lg font-bold text-blue-700">${totalInvestment.toLocaleString()}</p>
                </div>
                <div className="bg-red-50 border border-red-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <TrendingDown size={16} className="text-red-600" />
                        <span className="text-sm font-semibold text-red-600">Total Expenses</span>
                    </div>
                    <p className="text-lg font-bold text-red-700">${totalExpenses.toLocaleString()}</p>
                </div>
                <div className="bg-green-50 border border-green-200 p-3 rounded">
                    <div className="flex items-center gap-2">
                        <DollarSign size={16} className="text-green-600" />
                        <span className="text-sm font-semibold text-green-600">Total Returns</span>
                    </div>
                    <p className="text-lg font-bold text-green-700">${totalReturns.toLocaleString()}</p>
                </div>
                <div className={`border p-3 rounded ${netValue >= 0 ? 'bg-green-50 border-green-200' : 'bg-red-50 border-red-200'}`}>
                    <div className="flex items-center gap-2">
                        <DollarSign size={16} className={netValue >= 0 ? 'text-green-600' : 'text-red-600'} />
                        <span className={`text-sm font-semibold ${netValue >= 0 ? 'text-green-600' : 'text-red-600'}`}>Net Value</span>
                    </div>
                    <p className={`text-lg font-bold ${netValue >= 0 ? 'text-green-700' : 'text-red-700'}`}>
                        ${netValue.toLocaleString()}
                    </p>
                </div>
            </div>

            {/* Add Fund Form */}
            {isAddingFund && (
                <div className="bg-gray-50 border border-gray-200 p-4 rounded mb-4">
                    <h3 className="font-semibold mb-3">Add New Fund Record</h3>
                    <div className="grid grid-cols-2 gap-4">
                        <div>
                            <label className="block text-sm font-medium mb-1">Amount</label>
                            <input
                                type="number"
                                className="w-full p-2 border border-gray-300 rounded"
                                value={newFund.amount || ''}
                                onChange={(e) => setNewFund({ ...newFund, amount: parseFloat(e.target.value) })}
                                placeholder="Enter amount"
                            />
                        </div>
                        <div>
                            <label className="block text-sm font-medium mb-1">Category</label>
                            <select
                                className="w-full p-2 border border-gray-300 rounded"
                                value={newFund.category}
                                onChange={(e) => setNewFund({ ...newFund, category: e.target.value as any })}
                            >
                                <option value="investment">Investment</option>
                                <option value="expense">Expense</option>
                                <option value="return">Return</option>
                            </select>
                        </div>
                        <div className="col-span-2">
                            <label className="block text-sm font-medium mb-1">Description</label>
                            <input
                                type="text"
                                className="w-full p-2 border border-gray-300 rounded"
                                value={newFund.description || ''}
                                onChange={(e) => setNewFund({ ...newFund, description: e.target.value })}
                                placeholder="Enter description"
                            />
                        </div>
                        <div className="col-span-2">
                            <label className="block text-sm font-medium mb-1">Notes</label>
                            <textarea
                                className="w-full p-2 border border-gray-300 rounded"
                                value={newFund.notes || ''}
                                onChange={(e) => setNewFund({ ...newFund, notes: e.target.value })}
                                placeholder="Enter notes"
                                rows={3}
                            />
                        </div>
                    </div>
                    <div className="flex gap-2 mt-3">
                        <Button onClick={handleAddFund}>Add Record</Button>
                        <Button onClick={() => setIsAddingFund(false)}>Cancel</Button>
                    </div>
                </div>
            )}

            <div className="flex flex-1 gap-4">
                {/* Funds List */}
                <div className="w-1/2">
                    <div className="space-y-2 max-h-96 overflow-y-auto">
                        {funds.map((fund) => (
                            <div
                                key={fund.id}
                                className={`p-3 border cursor-pointer ${selectedFund?.id === fund.id
                                        ? 'bg-blue-100 border-blue-300'
                                        : 'bg-white border-gray-300 hover:bg-gray-50'
                                    }`}
                                onClick={() => setSelectedFund(fund)}
                            >
                                <div className="flex justify-between items-start">
                                    <div className="flex-1">
                                        <div className="flex items-center gap-2 mb-1">
                                            {getCategoryIcon(fund.category)}
                                            <span className="font-semibold">${fund.amount.toLocaleString()}</span>
                                            <span className={`px-2 py-1 text-xs rounded ${getCategoryColor(fund.category)}`}>
                                                {fund.category}
                                            </span>
                                        </div>
                                        <p className="text-sm text-gray-700">{fund.description}</p>
                                        <div className="flex items-center gap-4 text-xs text-gray-500 mt-1">
                                            <span className="flex items-center gap-1">
                                                <Calendar size={12} />
                                                {fund.date.toLocaleDateString()}
                                            </span>
                                            {fund.images && fund.images.length > 0 && (
                                                <span className="flex items-center gap-1">
                                                    <Image size={12} />
                                                    {fund.images.length} images
                                                </span>
                                            )}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        ))}
                    </div>
                </div>

                {/* Fund Details */}
                <div className="w-1/2 pl-4 border-l">
                    {selectedFund ? (
                        <div>
                            <h3 className="text-lg font-bold mb-4">Fund Details</h3>
                            <div className="space-y-3">
                                <div className="flex items-center gap-2">
                                    {getCategoryIcon(selectedFund.category)}
                                    <span className="font-semibold">Amount:</span>
                                    <span className="font-bold text-lg">${selectedFund.amount.toLocaleString()}</span>
                                </div>

                                <div>
                                    <span className="font-semibold">Description:</span>
                                    <p className="text-gray-600">{selectedFund.description}</p>
                                </div>

                                <div>
                                    <span className="font-semibold">Category:</span>
                                    <span className={`ml-2 px-2 py-1 text-xs rounded ${getCategoryColor(selectedFund.category)}`}>
                                        {selectedFund.category}
                                    </span>
                                </div>

                                <div className="flex items-center gap-2">
                                    <Calendar size={16} />
                                    <span className="font-semibold">Date:</span>
                                    <span>{selectedFund.date.toLocaleDateString()}</span>
                                </div>

                                {selectedFund.notes && (
                                    <div>
                                        <span className="font-semibold">Notes:</span>
                                        <p className="text-gray-600 mt-1">{selectedFund.notes}</p>
                                    </div>
                                )}

                                {/* Image Upload */}
                                <div className="mt-4">
                                    <span className="font-semibold mb-2 block">Upload Documents/Images:</span>
                                    <div className="border-2 border-dashed border-gray-300 rounded p-4 text-center">
                                        <input
                                            type="file"
                                            multiple
                                            accept="image/*,.pdf"
                                            onChange={handleImageUpload}
                                            className="hidden"
                                            id="image-upload"
                                        />
                                        <label htmlFor="image-upload" className="cursor-pointer">
                                            <Upload size={24} className="mx-auto mb-2 text-gray-400" />
                                            <p className="text-sm text-gray-600">Click to upload receipts, contracts, or documents</p>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    ) : (
                        <div className="flex items-center justify-center h-full text-gray-500">
                            Select a fund record to view details
                        </div>
                    )}
                </div>
            </div>
        </div>
    );
}
