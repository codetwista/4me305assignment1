<?php

namespace App\Controllers;


class PhotoController extends BaseController
{
    /**
     * List uploaded photos
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function index()
    {
        // Check for active user session
        if (! $this->session->has('isLoggedIn')) return redirect()->to(base_url('login'));
        
        return view('photos', [
            'title' => 'Photos',
            'photos' => $this->photoModel->where('user_id', $this->session->id)->findAll(),
            'errors' => []
        ]);
    }

    /**
     * Upload photo
     * @return \CodeIgniter\HTTP\RedirectResponse|string
     */
    public function upload()
    {
        // Check for active user session
        if (! $this->session->has('isLoggedIn')) return redirect()->to(base_url('login'));

        if ($this->request->is('post')) {
            $validationRule = [
                'caption' => [
                    'label' => 'Caption',
                    'rules' => [
                        'required',
                        'alpha_numeric_punct'
                    ],
                ],
                'photo' => [
                    'label' => 'Photo',
                    'rules' => [
                        'uploaded[photo]',
                        'is_image[photo]',
                        'mime_in[photo,image/jpg,image/jpeg,image/gif,image/png]',
                        'max_size[photo, 1024]'
                    ],
                ],
            ];
    
            if (! $this->validate($validationRule)) {
                $data = ['upload_errors' => $this->validator->getErrors(), 'title' => 'Upload photo'];
                return view('upload', $data);
            }
    
            $photo = $this->request->getFile('photo');
    
            if ($photo->isValid() && ! $photo->hasMoved()) {
                $newName = time() . '.' . $photo->guessExtension();

                if ($photo->move(FCPATH . 'uploads/', $newName)) {
                    // Save photo to database
                    $uploadData = [
                        'user_id' => $this->session->id,
                        'caption' => trim($this->request->getPost('caption', FILTER_SANITIZE_STRING)),
                        'url' => $newName,
                        'created_at' => $this->time->toDateTimeString()
                    ];
            
                    $this->photoModel->save($uploadData);
                    return redirect()->to('upload')->with('notice', '
                        <div class="ui success message">
                            <i class="close icon"></i>
                            <div class="header">
                                Photo uploaded successfully!
                            </div>
                            <p> <a href="' . base_url('twitter/login') . '">View photo</a></p>
                        </div>'
                    );
                }
            }
            $data = ['errors' => 'The file has already been moved!', 'title' => 'Upload photo'];
        }
        
        return view('upload', [
            'title' => 'Upload photo',
        ]);
    }
}
