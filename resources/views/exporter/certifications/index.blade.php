@extends('layouts.dashboard')
@section('title', 'Certifications')
@section('page-title', 'Certifications')
@section('sidebar-menu')
<div class="menu-section">
<div class="menu-section-title">Main</div>
<a href="{{ route('exporter.dashboard') }}" class="menu-item"><i class="fas fa-chart-line"></i><span>Dashboard</span></a>
<a href="{{ route('exporter.products') }}" class="menu-item"><i class="fas fa-box"></i><span>Products</span></a>
<a href="{{ route('exporter.categories') }}" class="menu-item"><i class="fas fa-tags"></i><span>Categories</span></a>
<a href="{{ route('exporter.brands') }}" class="menu-item"><i class="fas fa-copyright"></i><span>Brands</span></a>
<a href="{{ route('exporter.certifications') }}" class="menu-item active"><i class="fas fa-certificate"></i><span>Certifications</span></a>
</div>
<div class="menu-section">
<div class="menu-section-title">Account</div>
<a href="{{ route('profile.show') }}" class="menu-item"><i class="fas fa-user-circle"></i><span>Profile</span></a>
</div>
@endsection
@section('content')
<div class="mb-6 flex justify-between items-center">
<div><h2 class="text-2xl font-bold">Certifications</h2><p class="text-gray-600">Manage your certifications</p></div>
<button onclick="openModal()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700"><i class="fas fa-plus mr-2"></i>Add</button>
</div>
<div class="bg-white rounded-lg shadow">
<table class="min-w-full">
<thead class="bg-gray-50"><tr>
<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Name</th>
<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Code</th>
<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Authority</th>
<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Expiry</th>
<th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
<th class="px-6 py-3 text-center text-xs font-semibold text-gray-700 uppercase">Actions</th>
</tr></thead>
<tbody id="tbody">
@forelse($certifications as $c)
<tr id="row-{{$c->id}}">
<td class="px-6 py-4"><div class="font-semibold">{{$c->name}}</div><div class="text-sm text-gray-500">{{$c->certificate_number ?? '-'}}</div></td>
<td class="px-6 py-4">{{$c->code ?? '-'}}</td>
<td class="px-6 py-4">{{$c->issuing_authority ?? '-'}}</td>
<td class="px-6 py-4">{{$c->expiry_date ? $c->expiry_date->format('M d, Y') : 'No Expiry'}}</td>
<td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full {{$c->is_active?'bg-green-100 text-green-800':'bg-gray-100 text-gray-800'}}">{{$c->is_active?'Active':'Inactive'}}</span></td>
<td class="px-6 py-4 text-center">
<button onclick='edit(@json($c))' class="px-3 py-1 bg-blue-500 text-white rounded mr-2"><i class="fas fa-edit"></i></button>
<button onclick="del({{$c->id}},'{{$c->name}}')" class="px-3 py-1 bg-red-500 text-white rounded"><i class="fas fa-trash"></i></button>
</td>
</tr>
@empty
<tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No certifications</td></tr>
@endforelse
</tbody>
</table>
</div>
<div id="modal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl w-full max-w-2xl max-h-[95vh] overflow-hidden flex flex-col">
        <div class="px-8 pt-8 pb-4 border-b border-gray-200">
            <h3 id="title" class="text-2xl font-bold text-gray-800">Add Certification</h3>
        </div>
        <div class="px-8 py-6 overflow-y-auto flex-1">
            <form id="form">
                @csrf
                <input type="hidden" id="method" name="_method" value="POST">

                <div class="space-y-5">
                    <!-- Name Field -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" id="name" required
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <!-- Code and Certificate Number -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Code</label>
                            <input type="text" name="code" id="code"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Certificate Number</label>
                            <input type="text" name="certificate_number" id="number"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Issuing Authority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Issuing Authority</label>
                        <input type="text" name="issuing_authority" id="authority"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="desc" rows="3"
                                  class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition resize-none"></textarea>
                    </div>

                    <!-- Issue Date and Expiry Date -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Issue Date</label>
                            <input type="date" name="issue_date" id="issue"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Expiry Date</label>
                            <input type="date" name="expiry_date" id="expiry"
                                   class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                        </div>
                    </div>

                    <!-- Document Upload -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Document (PDF/Image, Max 5MB)</label>
                        <input type="file" name="document" id="doc" accept=".pdf,.jpg,.jpeg,.png"
                               class="w-full px-4 py-2.5 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition">
                    </div>

                    <!-- Active Checkbox -->
                    <div class="pt-2">
                        <label class="flex items-center cursor-pointer">
                            <input type="checkbox" name="is_active" id="active" value="1" checked
                                   class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-2 focus:ring-indigo-500">
                            <span class="ml-3 text-sm font-medium text-gray-700">Active</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <!-- Action Buttons -->
        <div class="px-8 py-6 border-t border-gray-200 bg-gray-50">
            <div class="flex gap-3">
                <button type="button" onclick="closeModal()"
                        class="flex-1 px-6 py-3 bg-white border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition">
                    Cancel
                </button>
                <button type="button" onclick="document.getElementById('form').requestSubmit()"
                        class="flex-1 px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition">
                    Save Certification
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-lg shadow-xl p-6 max-w-md w-full mx-4">
        <div class="text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">Delete Certification</h3>
            <p class="text-sm text-gray-500 mb-6">Are you sure you want to delete "<span id="deleteName" class="font-semibold text-gray-700"></span>"? This action cannot be undone.</p>
        </div>
        <div class="flex gap-3">
            <button type="button" onclick="closeDeleteModal()"
                    class="flex-1 px-4 py-2.5 bg-gray-100 text-gray-700 font-medium rounded-lg hover:bg-gray-200 transition">
                No, Cancel
            </button>
            <button type="button" onclick="confirmDelete()"
                    class="flex-1 px-4 py-2.5 bg-red-600 text-white font-medium rounded-lg hover:bg-red-700 transition">
                Yes, Delete
            </button>
        </div>
    </div>
</div>

<div id="toast" class="fixed top-4 right-4 hidden bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50"></div>
<script>
let editId=null;
let deleteId=null;

function openModal(){
    editId=null;
    document.getElementById('title').textContent='Add Certification';
    document.getElementById('method').value='POST';
    document.getElementById('form').reset();
    document.getElementById('active').checked=true;
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function edit(c){
    editId=c.id;
    document.getElementById('title').textContent='Edit Certification';
    document.getElementById('method').value='PUT';
    document.getElementById('name').value=c.name;
    document.getElementById('code').value=c.code||'';
    document.getElementById('number').value=c.certificate_number||'';
    document.getElementById('authority').value=c.issuing_authority||'';
    document.getElementById('desc').value=c.description||'';
    document.getElementById('issue').value=c.issue_date||'';
    document.getElementById('expiry').value=c.expiry_date||'';
    document.getElementById('active').checked=c.is_active;
    document.getElementById('modal').classList.remove('hidden');
    document.getElementById('modal').classList.add('flex');
}

function closeModal(){
    document.getElementById('modal').classList.add('hidden');
    document.getElementById('modal').classList.remove('flex');
}

function del(id,name){
    deleteId=id;
    document.getElementById('deleteName').textContent=name;
    document.getElementById('deleteModal').classList.remove('hidden');
    document.getElementById('deleteModal').classList.add('flex');
}

function closeDeleteModal(){
    document.getElementById('deleteModal').classList.add('hidden');
    document.getElementById('deleteModal').classList.remove('flex');
    deleteId=null;
}

function confirmDelete(){
    if(!deleteId) return;
    fetch('/exporter/certifications/'+deleteId,{
        method:'DELETE',
        headers:{'X-CSRF-TOKEN':'{{csrf_token()}}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{
        if(d.success){
            toast(d.message);
            document.getElementById('row-'+deleteId).remove();
            closeDeleteModal();
        }else{
            toast(d.message,'error');
        }
    }).catch(e=>{
        toast('Error','error');
        console.error(e);
    });
}

document.getElementById('form').addEventListener('submit',function(e){
    e.preventDefault();
    const fd=new FormData(this);
    const url=editId?'/exporter/certifications/'+editId:'{{route("exporter.certifications.store")}}';
    fetch(url,{
        method:'POST',
        body:fd,
        headers:{'X-CSRF-TOKEN':'{{csrf_token()}}','Accept':'application/json'}
    }).then(r=>r.json()).then(d=>{
        if(d.success){
            toast(d.message);
            closeModal();
            setTimeout(()=>location.reload(),1000);
        }else{
            toast(d.message||'Error','error');
        }
    }).catch(e=>{
        toast('Error','error');
        console.error(e);
    });
});

function toast(msg,type='success'){
    const t=document.getElementById('toast');
    t.className='fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 '+(type==='success'?'bg-green-500':'bg-red-500')+' text-white';
    t.textContent=msg;
    t.classList.remove('hidden');
    setTimeout(()=>t.classList.add('hidden'),3000);
}
</script>
@endsection
