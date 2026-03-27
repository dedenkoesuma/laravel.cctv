@extends('layouts.simple')

@section('title', 'Surat Order & Penawaran')

@section('content')
<style>
/* Main Container */
.sales-doc-container {
    min-height: 100vh;
    background: #f8f9fa;
    padding: 20px;
}

.sales-doc-header {
    background: white;
    padding: 25px;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    margin-bottom: 25px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.sales-doc-title {
    font-size: 28px;
    font-weight: 700;
    color: #333;
    margin: 0;
}

.sales-doc-actions {
    display: flex;
    gap: 10px;
}

.btn-back {
    padding: 10px 20px;
    background: #6c757d;
    color: white;
    border: none;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s;
}

.btn-back:hover {
    background: #5a6268;
    color: white;
}

.sales-doc-main {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.05);
    padding: 30px;
}

/* React App will be mounted here */
#sales-doc-app {
    min-height: 600px;
}

/* Override some styles for better integration */
.sales-doc-main .bg-gray-100 {
    background: transparent !important;
}

.sales-doc-main .max-w-7xl {
    max-width: 100% !important;
}

.sales-doc-main > div > div:first-child {
    margin: -30px -30px 0 -30px;
    padding: 30px;
}

/* Print styles */
@media print {
    .sales-doc-container,
    .sales-doc-header,
    .sales-doc-actions,
    .btn-back,
    .no-print {
        display: none !important;
    }
    
    .sales-doc-main {
        box-shadow: none;
        padding: 0;
        margin: 0;
    }
    
    body {
        background: white !important;
    }
}

/* Loading State */
.loading-spinner {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    min-height: 400px;
    gap: 20px;
}

.spinner {
    width: 50px;
    height: 50px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 768px) {
    .sales-doc-header {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .sales-doc-actions {
        width: 100%;
        justify-content: center;
    }
}
</style>

<div class="sales-doc-container">
    <div class="sales-doc-header no-print">
        <h1 class="sales-doc-title">📄 Surat Order & Penawaran</h1>
        <div class="sales-doc-actions">
            <a href="/admin/dashboard" class="btn-back">
                <i class="bi bi-arrow-left"></i> Kembali ke Dashboard
            </a>
        </div>
    </div>

    <div class="sales-doc-main">
        <div id="sales-doc-app">
            <div class="loading-spinner">
                <div class="spinner"></div>
                <p style="color: #666;">Memuat aplikasi...</p>
            </div>
        </div>
    </div>
</div>

<!-- React & Required Libraries -->
<script crossorigin src="https://unpkg.com/react@18/umd/react.production.min.js"></script>
<script crossorigin src="https://unpkg.com/react-dom@18/umd/react-dom.production.min.js"></script>
<script src="https://unpkg.com/@babel/standalone/babel.min.js"></script>

<!-- html2pdf.js for PDF generation -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.10.1/html2pdf.bundle.min.js"></script>

@verbatim
<script type="text/babel">
console.log('Script loading...');
console.log('React:', typeof React);
console.log('ReactDOM:', typeof ReactDOM);

const { useState } = React;

// Simple icon components as fallback
const Plus = () => <span style={{fontSize: '16px'}}>+</span>;
const Trash2 = () => <span style={{fontSize: '16px'}}>🗑️</span>;
const Printer = () => <span style={{fontSize: '16px'}}>🖨️</span>;
const FileText = () => <span style={{fontSize: '24px'}}>📄</span>;
const Download = () => <span style={{fontSize: '16px'}}>⬇️</span>;

function SalesDocumentCreator() {
  const [docType, setDocType] = useState('so');
  const [companyInfo, setCompanyInfo] = useState({
    name: 'PT. Contoh Perusahaan',
    address: 'Jl. Contoh No. 123, Jakarta',
    phone: '021-12345678',
    email: 'info@contoh.com',
    logo: ''
  });
  
  const [customerInfo, setCustomerInfo] = useState({
    name: '',
    address: '',
    phone: '',
    email: ''
  });
  
  const [docInfo, setDocInfo] = useState({
    number: '',
    date: new Date().toISOString().split('T')[0],
    dueDate: '',
    notes: '',
    includeTax: false,
    taxRate: 11,
    discount: ''
  });
  
  const [items, setItems] = useState([
    { id: 1, name: '', qty: '', unit: 'pcs', price: '', description: '' }
  ]);
  
  const [showPreview, setShowPreview] = useState(false);

  const handleLogoUpload = (e) => {
    const file = e.target.files[0];
    if (file) {
      const reader = new FileReader();
      reader.onloadend = () => {
        setCompanyInfo({...companyInfo, logo: reader.result});
      };
      reader.readAsDataURL(file);
    }
  };

  const addItem = () => {
    setItems([...items, { 
      id: Date.now(), 
      name: '', 
      qty: '', 
      unit: 'pcs', 
      price: '', 
      description: '' 
    }]);
  };

  const removeItem = (id) => {
    setItems(items.filter(item => item.id !== id));
  };

  const updateItem = (id, field, value) => {
    setItems(items.map(item => {
      if (item.id === id) {
        // Handle numeric fields - allow empty string for easier editing
        if (field === 'qty' || field === 'price') {
          const numValue = value === '' ? '' : parseFloat(value);
          return { ...item, [field]: numValue };
        }
        return { ...item, [field]: value };
      }
      return item;
    }));
  };

  const calculateSubtotal = (item) => {
    const qty = parseFloat(item.qty) || 0;
    const price = parseFloat(item.price) || 0;
    return qty * price;
  };

  const calculateTotal = () => {
    return items.reduce((sum, item) => sum + calculateSubtotal(item), 0);
  };

  const calculateDiscount = () => {
    const discount = parseFloat(docInfo.discount) || 0;
    return (calculateTotal() * discount) / 100;
  };

  const calculateTax = () => {
    const afterDiscount = calculateTotal() - calculateDiscount();
    const taxRate = parseFloat(docInfo.taxRate) || 0;
    return docInfo.includeTax ? (afterDiscount * taxRate) / 100 : 0;
  };

  const calculateGrandTotal = () => {
    return calculateTotal() - calculateDiscount() + calculateTax();
  };

  const formatCurrency = (amount) => {
    return new Intl.NumberFormat('id-ID', {
      style: 'currency',
      currency: 'IDR',
      minimumFractionDigits: 0
    }).format(amount);
  };

  const handlePrint = () => {
    window.print();
  };

  const handleDownloadPDF = (e) => {
    const element = document.getElementById('pdf-content');
    const docTypeLabel = docType === 'so' ? 'SO' : docType === 'quotation' ? 'Penawaran' : 'Invoice';
    const filename = `${docTypeLabel}_${docInfo.number || 'draft'}_${new Date().toISOString().split('T')[0]}.pdf`;
    
    const opt = {
      margin: [5, 5, 5, 5],
      filename: filename,
      image: { type: 'jpeg', quality: 0.98 },
      html2canvas: { 
        scale: 2, 
        useCORS: true,
        letterRendering: true,
        logging: false
      },
      jsPDF: { 
        unit: 'mm', 
        format: 'a4', 
        orientation: 'portrait',
        compress: true
      }
    };
    
    // Show loading indicator
    const button = e.currentTarget;
    const originalHTML = button.innerHTML;
    button.innerHTML = '⏳ Generating...';
    button.disabled = true;
    button.style.opacity = '0.6';
    button.style.cursor = 'not-allowed';
    
    html2pdf().set(opt).from(element).save().then(() => {
      button.innerHTML = originalHTML;
      button.disabled = false;
      button.style.opacity = '1';
      button.style.cursor = 'pointer';
    }).catch((error) => {
      console.error('PDF generation error:', error);
      button.innerHTML = originalHTML;
      button.disabled = false;
      button.style.opacity = '1';
      button.style.cursor = 'pointer';
      alert('Gagal membuat PDF. Silakan coba lagi.');
    });
  };

  const DocumentPreview = () => (
    <div className="bg-white p-8 shadow-lg" style={{ minHeight: '297mm' }}>
      <div className="border-b-2 border-gray-800 pb-4 mb-6 flex items-start justify-between">
        <div className="flex-1">
          <h1 className="text-2xl font-bold text-gray-800">{companyInfo.name}</h1>
          <p className="text-sm text-gray-600">{companyInfo.address}</p>
          <p className="text-sm text-gray-600">Tel: {companyInfo.phone} | Email: {companyInfo.email}</p>
        </div>
        {companyInfo.logo && (
          <img src={companyInfo.logo} alt="Logo" className="h-16 w-auto object-contain" />
        )}
      </div>

      <div className="text-center mb-6">
        <h2 className="text-xl font-bold text-gray-800 uppercase">
          {docType === 'so' ? 'SURAT ORDER (SO)' : docType === 'quotation' ? 'SURAT PENAWARAN HARGA' : 'INVOICE'}
        </h2>
        <p className="text-sm text-gray-600 mt-1">
          No: {docInfo.number || '-'}
        </p>
      </div>

      <div className="grid grid-cols-2 gap-4 mb-6">
        <div>
          <h3 className="font-semibold text-gray-700 mb-2">Kepada:</h3>
          <p className="text-sm">{customerInfo.name || '-'}</p>
          <p className="text-sm text-gray-600">{customerInfo.address || '-'}</p>
          <p className="text-sm text-gray-600">Tel: {customerInfo.phone || '-'}</p>
          <p className="text-sm text-gray-600">Email: {customerInfo.email || '-'}</p>
        </div>
        <div className="text-right">
          <p className="text-sm"><span className="font-semibold">Tanggal:</span> {docInfo.date}</p>
          {(docType === 'quotation' || docType === 'invoice') && docInfo.dueDate && (
            <p className="text-sm">
              <span className="font-semibold">
                {docType === 'quotation' ? 'Berlaku Hingga:' : 'Jatuh Tempo:'}
              </span> {docInfo.dueDate}
            </p>
          )}
        </div>
      </div>

      <table className="w-full border-collapse border border-gray-300 mb-6">
        <thead>
          <tr className="bg-gray-100">
            <th className="border border-gray-300 px-3 py-2 text-left text-sm">No</th>
            <th className="border border-gray-300 px-3 py-2 text-left text-sm">Nama Barang</th>
            <th className="border border-gray-300 px-3 py-2 text-left text-sm">Keterangan</th>
            <th className="border border-gray-300 px-3 py-2 text-center text-sm">Qty</th>
            <th className="border border-gray-300 px-3 py-2 text-center text-sm">Satuan</th>
            <th className="border border-gray-300 px-3 py-2 text-right text-sm">Harga</th>
            <th className="border border-gray-300 px-3 py-2 text-right text-sm">Subtotal</th>
          </tr>
        </thead>
        <tbody>
          {items.map((item, index) => (
            <tr key={item.id}>
              <td className="border border-gray-300 px-3 py-2 text-sm">{index + 1}</td>
              <td className="border border-gray-300 px-3 py-2 text-sm">{item.name || '-'}</td>
              <td className="border border-gray-300 px-3 py-2 text-sm">{item.description || '-'}</td>
              <td className="border border-gray-300 px-3 py-2 text-center text-sm">{item.qty || 0}</td>
              <td className="border border-gray-300 px-3 py-2 text-center text-sm">{item.unit}</td>
              <td className="border border-gray-300 px-3 py-2 text-right text-sm">{formatCurrency(parseFloat(item.price) || 0)}</td>
              <td className="border border-gray-300 px-3 py-2 text-right text-sm">{formatCurrency(calculateSubtotal(item))}</td>
            </tr>
          ))}
          <tr className="bg-gray-50">
            <td colSpan="6" className="border border-gray-300 px-3 py-2 text-right font-semibold text-sm">Subtotal</td>
            <td className="border border-gray-300 px-3 py-2 text-right font-semibold text-sm">{formatCurrency(calculateTotal())}</td>
          </tr>
          {(parseFloat(docInfo.discount) || 0) > 0 && (
            <tr>
              <td colSpan="6" className="border border-gray-300 px-3 py-2 text-right text-sm">Diskon ({parseFloat(docInfo.discount) || 0}%)</td>
              <td className="border border-gray-300 px-3 py-2 text-right text-sm">-{formatCurrency(calculateDiscount())}</td>
            </tr>
          )}
          {docInfo.includeTax && (
            <tr>
              <td colSpan="6" className="border border-gray-300 px-3 py-2 text-right text-sm">PPN ({parseFloat(docInfo.taxRate) || 0}%)</td>
              <td className="border border-gray-300 px-3 py-2 text-right text-sm">{formatCurrency(calculateTax())}</td>
            </tr>
          )}
          <tr className="bg-gray-100">
            <td colSpan="6" className="border border-gray-300 px-3 py-2 text-right font-bold text-sm">TOTAL</td>
            <td className="border border-gray-300 px-3 py-2 text-right font-bold text-sm">{formatCurrency(calculateGrandTotal())}</td>
          </tr>
        </tbody>
      </table>

      {docInfo.notes && (
        <div className="mb-6">
          <h3 className="font-semibold text-gray-700 mb-2">Catatan:</h3>
          <p className="text-sm text-gray-600 whitespace-pre-line">{docInfo.notes}</p>
        </div>
      )}

      <div style={{ display: 'flex', justifyContent: 'space-around', marginTop: '40px', gap: '30px' }}>
        <div style={{ textAlign: 'center', flex: 1 }}>
          <p className="text-sm font-semibold">Dibuat Oleh,</p>
          <div style={{ height: '70px' }}></div>
          <p className="text-sm font-semibold">(.....................)</p>
        </div>
        <div style={{ textAlign: 'center', flex: 1 }}>
          <p className="text-sm font-semibold">Disetujui Oleh,</p>
          <div style={{ height: '70px' }}></div>
          <p className="text-sm font-semibold">(.....................)</p>
        </div>
        <div style={{ textAlign: 'center', flex: 1 }}>
          <p className="text-sm font-semibold">Diterima Oleh,</p>
          <div style={{ height: '70px' }}></div>
          <p className="text-sm font-semibold">(.....................)</p>
        </div>
      </div>
    </div>
  );

  return (
    <div style={{ minHeight: '600px' }}>
      <style>{`
        @media print {
          body * {
            visibility: hidden;
          }
          .print-area, .print-area * {
            visibility: visible;
          }
          .print-area {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
          }
          .no-print {
            display: none !important;
          }
        }
      `}</style>

      <div className={`${showPreview ? 'no-print' : ''}`}>
        <div style={{ background: 'white', borderRadius: '15px', padding: '30px', marginBottom: '20px' }}>
          <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '25px' }}>
            <h2 style={{ fontSize: '24px', fontWeight: '700', color: '#333', margin: 0, display: 'flex', alignItems: 'center', gap: '10px' }}>
              <FileText />
              Pembuat Dokumen
            </h2>
            <div style={{ display: 'flex', gap: '10px' }}>
              <button
                onClick={() => setShowPreview(!showPreview)}
                style={{
                  padding: '12px 24px',
                  background: '#667eea',
                  color: 'white',
                  border: 'none',
                  borderRadius: '8px',
                  fontWeight: '600',
                  cursor: 'pointer',
                  transition: 'all 0.3s'
                }}
                onMouseOver={(e) => e.target.style.background = '#5568d3'}
                onMouseOut={(e) => e.target.style.background = '#667eea'}
              >
                {showPreview ? 'Edit' : 'Preview'}
              </button>
              {showPreview && (
                <>
                  <button
                    onClick={handlePrint}
                    style={{
                      padding: '12px 24px',
                      background: '#10b981',
                      color: 'white',
                      border: 'none',
                      borderRadius: '8px',
                      fontWeight: '600',
                      cursor: 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '8px',
                      transition: 'all 0.3s'
                    }}
                    onMouseOver={(e) => e.target.style.background = '#059669'}
                    onMouseOut={(e) => e.target.style.background = '#10b981'}
                  >
                    <Printer />
                    {' '}Print
                  </button>
                  <button
                    onClick={handleDownloadPDF}
                    style={{
                      padding: '12px 24px',
                      background: '#f59e0b',
                      color: 'white',
                      border: 'none',
                      borderRadius: '8px',
                      fontWeight: '600',
                      cursor: 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '8px',
                      transition: 'all 0.3s'
                    }}
                    onMouseOver={(e) => e.target.style.background = '#d97706'}
                    onMouseOut={(e) => e.target.style.background = '#f59e0b'}
                  >
                    <Download />
                    {' '}Download PDF
                  </button>
                </>
              )}
            </div>
          </div>

          {!showPreview && (
            <>
              <div style={{ marginBottom: '25px' }}>
                <label style={{ display: 'block', fontSize: '14px', fontWeight: '600', color: '#333', marginBottom: '10px' }}>
                  Jenis Dokumen
                </label>
                <div style={{ display: 'flex', gap: '12px' }}>
                  {['so', 'quotation', 'invoice'].map((type) => (
                    <button
                      key={type}
                      onClick={() => setDocType(type)}
                      style={{
                        padding: '12px 24px',
                        borderRadius: '8px',
                        fontWeight: '600',
                        border: 'none',
                        cursor: 'pointer',
                        background: docType === type ? '#667eea' : '#e9ecef',
                        color: docType === type ? 'white' : '#495057',
                        transition: 'all 0.3s'
                      }}
                    >
                      {type === 'so' ? 'Surat Order (SO)' : type === 'quotation' ? 'Surat Penawaran' : 'Invoice'}
                    </button>
                  ))}
                </div>
              </div>

              <div style={{ marginBottom: '25px' }}>
                <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', marginBottom: '15px' }}>
                  Informasi Perusahaan
                </h3>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: '15px' }}>
                  <div style={{ gridColumn: '1 / -1' }}>
                    <label style={{ display: 'block', fontSize: '13px', fontWeight: '500', color: '#666', marginBottom: '8px' }}>
                      Logo Perusahaan
                    </label>
                    <input
                      type="file"
                      accept="image/*"
                      onChange={handleLogoUpload}
                      style={{
                        width: '100%',
                        padding: '10px',
                        border: '1px solid #ddd',
                        borderRadius: '8px',
                        fontSize: '14px'
                      }}
                    />
                    {companyInfo.logo && (
                      <div style={{ marginTop: '10px' }}>
                        <img src={companyInfo.logo} alt="Logo Preview" style={{ height: '64px', width: 'auto', objectFit: 'contain', border: '1px solid #ddd', borderRadius: '8px', padding: '5px' }} />
                      </div>
                    )}
                  </div>
                  <input
                    type="text"
                    placeholder="Nama Perusahaan"
                    value={companyInfo.name}
                    onChange={(e) => setCompanyInfo({...companyInfo, name: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  <input
                    type="text"
                    placeholder="Telepon"
                    value={companyInfo.phone}
                    onChange={(e) => setCompanyInfo({...companyInfo, phone: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  <input
                    type="text"
                    placeholder="Alamat"
                    value={companyInfo.address}
                    onChange={(e) => setCompanyInfo({...companyInfo, address: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  <input
                    type="email"
                    placeholder="Email"
                    value={companyInfo.email}
                    onChange={(e) => setCompanyInfo({...companyInfo, email: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                </div>
              </div>

              <div style={{ marginBottom: '25px' }}>
                <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', marginBottom: '15px' }}>
                  Informasi Pelanggan
                </h3>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(2, 1fr)', gap: '15px' }}>
                  <input
                    type="text"
                    placeholder="Nama Pelanggan"
                    value={customerInfo.name}
                    onChange={(e) => setCustomerInfo({...customerInfo, name: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  <input
                    type="text"
                    placeholder="Telepon"
                    value={customerInfo.phone}
                    onChange={(e) => setCustomerInfo({...customerInfo, phone: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  <input
                    type="text"
                    placeholder="Alamat"
                    value={customerInfo.address}
                    onChange={(e) => setCustomerInfo({...customerInfo, address: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  <input
                    type="email"
                    placeholder="Email"
                    value={customerInfo.email}
                    onChange={(e) => setCustomerInfo({...customerInfo, email: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                </div>
              </div>

              <div style={{ marginBottom: '25px' }}>
                <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', marginBottom: '15px' }}>
                  Informasi Dokumen
                </h3>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '15px', marginBottom: '15px' }}>
                  <input
                    type="text"
                    placeholder="Nomor Dokumen"
                    value={docInfo.number}
                    onChange={(e) => setDocInfo({...docInfo, number: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  <input
                    type="date"
                    value={docInfo.date}
                    onChange={(e) => setDocInfo({...docInfo, date: e.target.value})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                  {(docType === 'quotation' || docType === 'invoice') && (
                    <input
                      type="date"
                      placeholder="Berlaku Hingga / Jatuh Tempo"
                      value={docInfo.dueDate}
                      onChange={(e) => setDocInfo({...docInfo, dueDate: e.target.value})}
                      style={{
                        padding: '12px',
                        border: '1px solid #ddd',
                        borderRadius: '8px',
                        fontSize: '14px'
                      }}
                    />
                  )}
                </div>
                <div style={{ display: 'grid', gridTemplateColumns: 'repeat(3, 1fr)', gap: '15px' }}>
                  <div>
                    <label style={{ display: 'flex', alignItems: 'center', gap: '8px', fontSize: '14px', cursor: 'pointer' }}>
                      <input
                        type="checkbox"
                        checked={docInfo.includeTax}
                        onChange={(e) => setDocInfo({...docInfo, includeTax: e.target.checked})}
                        style={{ width: '16px', height: '16px', cursor: 'pointer' }}
                      />
                      <span>Termasuk PPN</span>
                    </label>
                  </div>
                  {docInfo.includeTax && (
                    <input
                      type="number"
                      placeholder="% PPN"
                      value={docInfo.taxRate}
                      onChange={(e) => setDocInfo({...docInfo, taxRate: e.target.value === '' ? '' : parseFloat(e.target.value)})}
                      style={{
                        padding: '12px',
                        border: '1px solid #ddd',
                        borderRadius: '8px',
                        fontSize: '14px'
                      }}
                    />
                  )}
                  <input
                    type="number"
                    placeholder="Diskon (%)"
                    value={docInfo.discount}
                    onChange={(e) => setDocInfo({...docInfo, discount: e.target.value === '' ? '' : parseFloat(e.target.value)})}
                    style={{
                      padding: '12px',
                      border: '1px solid #ddd',
                      borderRadius: '8px',
                      fontSize: '14px'
                    }}
                  />
                </div>
              </div>

              <div style={{ marginBottom: '25px' }}>
                <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', marginBottom: '15px' }}>
                  <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', margin: 0 }}>
                    Daftar Barang
                  </h3>
                  <button
                    onClick={addItem}
                    style={{
                      padding: '10px 20px',
                      background: '#10b981',
                      color: 'white',
                      border: 'none',
                      borderRadius: '8px',
                      fontWeight: '600',
                      cursor: 'pointer',
                      display: 'flex',
                      alignItems: 'center',
                      gap: '8px',
                      transition: 'all 0.3s'
                    }}
                    onMouseOver={(e) => e.target.style.background = '#059669'}
                    onMouseOut={(e) => e.target.style.background = '#10b981'}
                  >
                    <Plus />
                    {' '}Tambah Barang
                  </button>
                </div>
                <div style={{ display: 'flex', flexDirection: 'column', gap: '12px' }}>
                  {items.map((item, index) => (
                    <div key={item.id} style={{ display: 'grid', gridTemplateColumns: '2fr 2fr 1fr 1fr 1.5fr 1.5fr auto', gap: '10px', alignItems: 'start', background: '#f8f9fa', padding: '15px', borderRadius: '8px' }}>
                      <input
                        type="text"
                        placeholder="Nama Barang"
                        value={item.name}
                        onChange={(e) => updateItem(item.id, 'name', e.target.value)}
                        style={{
                          padding: '10px',
                          border: '1px solid #ddd',
                          borderRadius: '6px',
                          fontSize: '13px'
                        }}
                      />
                      <input
                        type="text"
                        placeholder="Keterangan"
                        value={item.description}
                        onChange={(e) => updateItem(item.id, 'description', e.target.value)}
                        style={{
                          padding: '10px',
                          border: '1px solid #ddd',
                          borderRadius: '6px',
                          fontSize: '13px'
                        }}
                      />
                      <input
                        type="number"
                        placeholder="Qty"
                        value={item.qty}
                        onChange={(e) => updateItem(item.id, 'qty', e.target.value)}
                        style={{
                          padding: '10px',
                          border: '1px solid #ddd',
                          borderRadius: '6px',
                          fontSize: '13px'
                        }}
                      />
                      <input
                        type="text"
                        placeholder="Unit"
                        value={item.unit}
                        onChange={(e) => updateItem(item.id, 'unit', e.target.value)}
                        style={{
                          padding: '10px',
                          border: '1px solid #ddd',
                          borderRadius: '6px',
                          fontSize: '13px'
                        }}
                      />
                      <input
                        type="number"
                        placeholder="Harga"
                        value={item.price}
                        onChange={(e) => updateItem(item.id, 'price', e.target.value)}
                        style={{
                          padding: '10px',
                          border: '1px solid #ddd',
                          borderRadius: '6px',
                          fontSize: '13px'
                        }}
                      />
                      <div style={{ display: 'flex', alignItems: 'center', justifyContent: 'space-between', padding: '10px' }}>
                        <span style={{ fontSize: '13px', fontWeight: '600', color: '#333' }}>
                          {formatCurrency(calculateSubtotal(item))}
                        </span>
                      </div>
                      <button
                        onClick={() => removeItem(item.id)}
                        style={{
                          padding: '10px',
                          background: '#ef4444',
                          color: 'white',
                          border: 'none',
                          borderRadius: '6px',
                          cursor: 'pointer',
                          display: 'flex',
                          alignItems: 'center',
                          justifyContent: 'center',
                          transition: 'all 0.3s'
                        }}
                        onMouseOver={(e) => e.target.style.background = '#dc2626'}
                        onMouseOut={(e) => e.target.style.background = '#ef4444'}
                      >
                        <Trash2 />
                      </button>
                    </div>
                  ))}
                </div>
                <div style={{ marginTop: '20px', background: '#f8f9fa', padding: '20px', borderRadius: '8px' }}>
                  <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px', fontSize: '14px' }}>
                    <span style={{ color: '#666' }}>Subtotal:</span>
                    <span style={{ fontWeight: '600' }}>{formatCurrency(calculateTotal())}</span>
                  </div>
                  {(parseFloat(docInfo.discount) || 0) > 0 && (
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px', fontSize: '14px' }}>
                      <span style={{ color: '#666' }}>Diskon ({parseFloat(docInfo.discount) || 0}%):</span>
                      <span style={{ fontWeight: '600', color: '#ef4444' }}>-{formatCurrency(calculateDiscount())}</span>
                    </div>
                  )}
                  {docInfo.includeTax && (
                    <div style={{ display: 'flex', justifyContent: 'space-between', marginBottom: '10px', fontSize: '14px' }}>
                      <span style={{ color: '#666' }}>PPN ({parseFloat(docInfo.taxRate) || 0}%):</span>
                      <span style={{ fontWeight: '600' }}>{formatCurrency(calculateTax())}</span>
                    </div>
                  )}
                  <div style={{ display: 'flex', justifyContent: 'space-between', paddingTop: '10px', borderTop: '2px solid #ddd', fontSize: '18px' }}>
                    <span style={{ fontWeight: '700', color: '#333' }}>Total:</span>
                    <span style={{ fontWeight: '700', color: '#667eea' }}>{formatCurrency(calculateGrandTotal())}</span>
                  </div>
                </div>
              </div>

              <div>
                <h3 style={{ fontSize: '18px', fontWeight: '600', color: '#333', marginBottom: '15px' }}>
                  Catatan
                </h3>
                <textarea
                  placeholder="Tambahkan catatan tambahan..."
                  value={docInfo.notes}
                  onChange={(e) => setDocInfo({...docInfo, notes: e.target.value})}
                  rows="4"
                  style={{
                    width: '100%',
                    padding: '12px',
                    border: '1px solid #ddd',
                    borderRadius: '8px',
                    fontSize: '14px',
                    fontFamily: 'inherit',
                    resize: 'vertical'
                  }}
                />
              </div>
            </>
          )}
        </div>
      </div>

      {showPreview && (
        <div className="print-area" id="pdf-content">
          <DocumentPreview />
        </div>
      )}
    </div>
  );
}

console.log('Creating React root...');
const root = ReactDOM.createRoot(document.getElementById('sales-doc-app'));
console.log('Rendering component...');
root.render(<SalesDocumentCreator />);
console.log('Component rendered!');
</script>
@endverbatim

@endsection