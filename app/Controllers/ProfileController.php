<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function __construct(
        private readonly UserModel $userModel = new UserModel(),
    ) {
    }

    public function index(): string
    {
        return $this->page('Profile/ProfilePage', [
            'pageTitle' => 'Profil Saya',
        ]);
    }

    public function uploadPhoto()
    {
        $currentUser = $this->authService->currentUser();

        if ($currentUser === null) {
            return redirect()->to(base_url('login'))->with('error', 'Sesi login tidak ditemukan.');
        }

        $photo = $this->request->getFile('profilePhoto');

        if ($photo === null || ! $photo->isValid() || $photo->hasMoved()) {
            return redirect()->back()->with('error', 'Foto profil tidak valid.');
        }

        $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/webp'];
        if (! in_array($photo->getMimeType(), $allowedMimeTypes, true)) {
            return redirect()->back()->with('error', 'Format foto profil harus JPG, PNG, atau WEBP.');
        }

        if ($photo->getSizeByUnit('mb') > 3) {
            return redirect()->back()->with('error', 'Ukuran foto profil maksimal 3 MB.');
        }

        $uploadPath = FCPATH . 'Uploads/Profile/';
        if (! is_dir($uploadPath)) {
            mkdir($uploadPath, 0775, true);
        }

        $newName = $photo->getRandomName();
        $photo->move($uploadPath, $newName);
        $newRelativePath = 'Uploads/Profile/' . $newName;

        $existingUser = $this->userModel->findDetailedById((int) $currentUser['id']);
        $existingPhoto = trim((string) ($existingUser['profile_photo_path'] ?? ''));

        $this->userModel->update((int) $currentUser['id'], [
            'profile_photo_path' => $newRelativePath,
        ]);

        if ($existingPhoto !== '') {
            $oldFile = FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $existingPhoto);
            if (is_file($oldFile)) {
                @unlink($oldFile);
            }
        }

        return redirect()->to(base_url('profile'))->with('success', 'Foto profil berhasil diperbarui.');
    }
}
