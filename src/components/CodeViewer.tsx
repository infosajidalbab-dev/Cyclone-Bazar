import React, { useState } from 'react';
import { PHASE_1_FILES, CodeFile } from '../data/phase1Codebase';
import { Copy, Check, FileCode, Layers, ShieldCheck, Download } from 'lucide-react';

export const CodeViewer: React.FC = () => {
  const [selectedFileId, setSelectedFileId] = useState<string>(PHASE_1_FILES[0].id);
  const [copied, setCopied] = useState<boolean>(false);
  const [activeTab, setActiveTab] = useState<'all' | 'migration' | 'model' | 'contract' | 'service'>('all');

  const selectedFile = PHASE_1_FILES.find(f => f.id === selectedFileId) || PHASE_1_FILES[0];

  const handleCopy = () => {
    navigator.clipboard.writeText(selectedFile.code);
    setCopied(true);
    setTimeout(() => setCopied(false), 2000);
  };

  const filteredFiles = PHASE_1_FILES.filter(f => activeTab === 'all' || f.category === activeTab);

  return (
    <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
      {/* File Tree / Selector */}
      <div className="lg:col-span-4 space-y-4">
        <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-sm">
          <div className="flex items-center justify-between mb-3">
            <h3 className="font-semibold text-slate-900 text-sm flex items-center gap-2">
              <FileCode className="w-4 h-4 text-[#0F4C81]" />
              <span>Phase 1 Architecture Files</span>
            </h3>
            <span className="text-[11px] font-mono text-slate-500">{filteredFiles.length} files</span>
          </div>

          {/* Category Tabs */}
          <div className="flex flex-wrap gap-1 mb-3 pb-2 border-b border-slate-100">
            {(['all', 'migration', 'model', 'contract', 'service'] as const).map(tab => (
              <button
                key={tab}
                onClick={() => setActiveTab(tab)}
                className={`text-[11px] px-2.5 py-1 rounded-md capitalize transition-colors ${
                  activeTab === tab
                    ? 'bg-[#0F4C81] text-white font-medium'
                    : 'text-slate-600 hover:bg-slate-100'
                }`}
              >
                {tab === 'all' ? 'All Files' : `${tab}s`}
              </button>
            ))}
          </div>

          {/* Files List */}
          <div className="space-y-1 max-h-[480px] overflow-y-auto pr-1">
            {filteredFiles.map(file => {
              const isSelected = file.id === selectedFile.id;
              return (
                <button
                  key={file.id}
                  onClick={() => setSelectedFileId(file.id)}
                  className={`w-full text-left p-2.5 rounded-lg text-xs transition-all flex flex-col gap-0.5 ${
                    isSelected
                      ? 'bg-blue-50 border border-blue-200 text-[#0F4C81]'
                      : 'hover:bg-slate-50 border border-transparent text-slate-700'
                  }`}
                >
                  <div className="flex items-center justify-between">
                    <span className="font-mono font-medium truncate">{file.name}</span>
                    <span className="text-[10px] text-slate-500 uppercase">{file.category}</span>
                  </div>
                  <span className="text-[11px] text-slate-500 font-mono truncate">{file.path}</span>
                </button>
              );
            })}
          </div>
        </div>

        {/* Highlight details for selected file */}
        <div className="bg-white border border-slate-200 rounded-xl p-4 shadow-sm space-y-3">
          <div className="text-xs font-semibold text-slate-900 flex items-center gap-1.5">
            <ShieldCheck className="w-4 h-4 text-emerald-600" />
            <span>Key Architectural Features</span>
          </div>
          <p className="text-xs text-slate-600">{selectedFile.summary}</p>
          <ul className="space-y-1.5 text-xs text-slate-600">
            {selectedFile.keyFeatures.map((feat, i) => (
              <li key={i} className="flex items-start gap-2">
                <span className="text-emerald-500 font-bold">•</span>
                <span>{feat}</span>
              </li>
            ))}
          </ul>
          {selectedFile.indexes && selectedFile.indexes.length > 0 && (
            <div className="pt-2 border-t border-slate-100">
              <span className="text-[11px] font-semibold text-slate-700 block mb-1">Defined Indexes:</span>
              <div className="space-y-1">
                {selectedFile.indexes.map((idx, i) => (
                  <div key={i} className="font-mono text-[11px] bg-slate-50 text-slate-700 p-1.5 rounded border border-slate-200">
                    {idx}
                  </div>
                ))}
              </div>
            </div>
          )}
        </div>
      </div>

      {/* Code Display Area */}
      <div className="lg:col-span-8 space-y-3">
        <div className="bg-slate-950 text-slate-100 rounded-xl border border-slate-800 shadow-md overflow-hidden flex flex-col">
          {/* Header Bar */}
          <div className="px-4 py-3 bg-slate-900 border-b border-slate-800 flex items-center justify-between">
            <div className="flex items-center gap-2">
              <span className="w-2.5 h-2.5 rounded-full bg-red-500/80 inline-block" />
              <span className="w-2.5 h-2.5 rounded-full bg-amber-500/80 inline-block" />
              <span className="w-2.5 h-2.5 rounded-full bg-emerald-500/80 inline-block" />
              <span className="ml-2 font-mono text-xs text-slate-300">{selectedFile.path}</span>
            </div>

            <button
              onClick={handleCopy}
              className="flex items-center gap-1.5 px-3 py-1.5 rounded bg-slate-800 hover:bg-slate-700 text-xs font-mono text-slate-200 transition-colors border border-slate-700"
            >
              {copied ? (
                <>
                  <Check className="w-3.5 h-3.5 text-emerald-400" />
                  <span>Copied!</span>
                </>
              ) : (
                <>
                  <Copy className="w-3.5 h-3.5" />
                  <span>Copy Code</span>
                </>
              )}
            </button>
          </div>

          {/* Code Viewer */}
          <div className="p-4 overflow-x-auto max-h-[640px] font-mono text-xs leading-relaxed text-slate-200 bg-slate-950">
            <pre>
              <code>{selectedFile.code}</code>
            </pre>
          </div>
        </div>
      </div>
    </div>
  );
};
